<?php
/**
 * Author:: JoeZ99 (<jzarate@gmail.com>).
 * Author:: @carlosescri (http://github.com/carlosescri)
 *
 * All credit goes to Gilles Devaux (<gilles.devaux@gmail.com>)
 * (https://github.com/flaptor/indextank-php)
 *
 * License:: Apache License, Version 2.0
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

namespace Doofinder\Search;

use Doofinder\Search\Results;
use Doofinder\Search\Error;

/**
 * Doofinder\Search\Client
 *
 * Basic client to perform requests to Doofinder search servers.
 */
class Client {
  const DEFAULT_RPP = 10;
  const OK = '"OK"';

  private $_baseUrl = null;
  private $_hashid = null;
  private $_token = null;

  private $_customHeaders = array();
  private $_searchParams = array();

  private $_page = 1;
  private $_total = null;

  public function __construct($hashid, $server, $token) {
    $this->_hashid = $hashid;
    if (!preg_match('/^https?:/', $server)) {
      $this->_baseUrl = sprintf('https://%s/5', $server);
    } else {
      $this->_baseUrl = sprintf('%s/5', $server);
    }
    $this->_token = $token;
  }

  /**
   * Load status from request.
   *
   * @param array $params Search parameters ($_REQUEST, $_GET or $_POST).
   */
  public function load($params, $options = array()) {
    $serializationOptions = $this->_buildSerializationOptions($options);
    $prefix = $serializationOptions['prefix'];
    $queryParameter = $serializationOptions['queryParameter'];

    foreach ($params as $key => $value) {
      if ($key === $prefix.$queryParameter) {
        $this->_searchParams['query'] = $value;
      } else if ($key = $this->_unprefixParam($key, $prefix)) {
        $this->_searchParams[$key] = $value;
      }
    }

    return $this->_searchParams;
  }

  /**
   * Dump current search params to an assoc array.
   */
  public function dump($page = null, $options = array()) {
    $serializationOptions = $this->_buildSerializationOptions($options);
    $prefix = $serializationOptions['prefix'];
    $queryParameter = $serializationOptions['queryParameter'];
    $params = array();

    foreach ($this->_searchParams as $key => $value) {
      if ($key === 'query') {
        $params[$prefix.$queryParameter] = $value;
      } else {
        $params[$prefix . $key] = $value;
      }
    }

    if (!is_null($page)) {
      $params[$prefix . 'page'] = $page;
    }

    return $params;
  }

  /**
   * qs
   *
   * 'serialize' the object's state to querystring params
   * @param int $page the pagenumber. defaults to the current page
   */
  public function qs($page = null, $options = array()){
    $params = $this->dump($page, $options);
    return http_build_query($params, '', '&');
  }

  public function reset() {
    $this->_searchParams = array();
  }

  private function _buildSerializationOptions($options = array()) {
    return array_merge(
      array(
        'prefix' => '',
        'queryParameter' => 'query',
      ),
      $options
    );
  }

  private function _unprefixParam($name, $prefix = '') {
    $unprefixPattern = '/^'.$prefix.'/';
    $doofinderParams = array(
      'page', 'rpp', 'timeout', 'types',
      'filter', 'query_name', 'transformer',
      'sort', 'exclude'
    );
    $pos = strpos($name, '[');

    if ($pos > 0) {
      $key = substr($name, 0, $pos);
    } else {
      $key = $name;
    }

    $key = preg_replace($unprefixPattern, '', $key);

    if (in_array($key, $doofinderParams)) {
      return preg_replace($unprefixPattern, '', $name);
    } else {
      return null;
    }
  }

  private function _getRequestUrl($entryPoint, $params = array()) {
    return sprintf('%s/%s?%s', $this->_baseUrl, $entryPoint, http_build_query($this->_sanitize($params), '', '&'));
  }

  private function _getRequestHeaders(){
    $headers = array();
    $headers[] = 'Expect:'; // Fixes HTTP/1.1 "417 Expectation Failed" Error
    $headers[] = sprintf("Authorization: Token %s", $this->_token);

    foreach($this->_customHeaders as $name => $value){
      $headers[] = sprintf("%s: %s", $name, $value);
    }
    return $headers;
  }

  private function _request($entryPoint, $params = array()){
    // TODO: in the future this class shouldn't be tied to hashid
    $params['hashid'] = $this->_hashid;

    $url = $this->_getRequestUrl($entryPoint, $params);
    $headers = $this->_getRequestHeaders();

    $session = curl_init($url);
    // Configure cURL to return response but not headers
    curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($session);
    $statusCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
    curl_close($session);

    if (floor($statusCode / 100) == 2) {
      return $response;
    } else {
      throw new Error($statusCode.' - '.$response, $statusCode);
    }
  }

  public function options() {
    return $this->_request('options/'.$this->_hashid);
  }

  /**
   * query. makes the query to the doofinder search server.
   * also set several search parameters through it's $options argument
   *
   * @param string $query the search query
   * @param int $page the page number or the results to show
   * @param array $options query options:
   *        - 'rpp'=> number of results per page. default 10
   *        - 'timeout' => timeout after which the search server drops the conn.
   *                       defaults to 10 seconds
   *        - 'types' => types of index to search at. default: all.
   *        - 'filter' => filter to apply. ['color'=>['red','blue'], 'price'=>['from'=>33]]
   *        - 'exclude' => exclude ['color' => ['yellow']]
   *        - any other param will be sent as a request parameter
   * @return DoofinderResults results
   */
  public function search($query = null, $page = null, $extraParams = array()) {
    if (!is_null($query)) {
      $this->_searchParams['query'] = $query;
    } else {
      unset($this->_searchParams['query']);
    }

    if (!is_null($page)) {
      $this->_searchParams['page'] = intval($page);
    } else {
      $this->_searchParams['page'] = 1;
    }

    if ($this->_searchParams['page'] === 1) {
      unset($this->_searchParams['query_name']);
    }

    if (!trim($this->getSearchParam('query', ''))){
      $this->_searchParams['query_name'] = 'match_all';
    }

    foreach ($extraParams as $key => $value) {
      $this->_searchParams[$key] = $value;
    }

    $results = new Results($this->_request('/search', $this->dump()));
    $this->_searchParams['query'] = $results->getProperty('query');
    $this->_searchParams['query_name'] = $results->getProperty('query_name');
    $this->_total = $results->getProperty('total');

    return $results;
  }

  /**
   * hasNextPage
   *
   * @return boolean true if there is another page of results
   */
  public function hasNextPage(){
    return $this->getPage() < $this->getLastPage();
  }

  /**
   * hasPreviousPage
   *
   * @return true if there is a previous page of results
   */
  public function hasPreviousPage(){
    return $this->getPage() > 1;
  }

  /**
   * getPage
   *
   * obtain the current page number
   * @return int the page number
   */
  public function getPage(){
    return $this->getSearchParam('page', 1);
  }

  // Filters

  public function getFilters() {
    return $this->_getFilters('filter');
  }

  public function hasFilter($name) {
    return $this->_hasFilter('filter', $name);
  }

  public function getFilter($name) {
    return $this->_getFilter('filter', $name);
  }

  public function setFilter($name, $value) {
    $this->_setFilter('filter', $name, $value);
  }

  public function removeFilter($name) {
    $this->_removeFilter('filter', $name);
  }

  // Exclusions

  public function getExclusionFilters() {
    return $this->_getFilters('exclude');
  }

  public function hasExclusionFilter($name) {
    return $this->_hasFilter('exclude', $name);
  }

  public function getExclusionFilter($name) {
    return $this->_getFilter('exclude', $name);
  }

  public function setExclusionFilter($name, $value) {
    $this->_setFilter('exclude', $name, $value);
  }

  public function removeExclusionFilter($name) {
    $this->_removeFilter('exclude', $name);
  }

  // Private stuff for filters

  private function _getFilters($bucket) {
    return (array) $this->_searchParams[$bucket];
  }

  private function _hasFilter($bucket, $name) {
    return isset($this->_searchParams[$bucket][$name]);
  }

  private function _getFilter($bucket, $name) {
    return $this->_hasFilter($bucket, $name) ? $this->_searchParams[$bucket][$name] : null;
  }

  private function _setFilter($bucket, $name, $value) {
    $this->_searchParams[$bucket][$name] = (array) $value;
  }

  private function _removeFilter($bucket, $name) {
    unset($this->_searchParams[$bucket][$name]);
  }

  // sorting

  public function setSorting($sortingList = array()) {
    foreach ($sortingList as $sorting) {
      list($field, $direction) = $sorting;
      $this->_searchParams['sort'][] = array($field => $direction);
    }
  }

  /**
   * Cleans $params array by removing keys with empty/null values.
   *
   * @param   array $params Array to be cleaned.
   * @return  array         Array with no empty keys.
   */
  private function _sanitize($params) {
    $result = array();

    foreach ($params as $name => $value) {
      if (is_array($value)) {
        $result[$name] = $this->_sanitize($value);
      } else if (trim($value)) {
        $result[$name] = $value;
      } else if($value === 0) {
        $result[$name] = $value;
      }
    }

    return $result;
  }

  /**
   * getSearchParam
   *
   * checks whether a search option is defined in $this->_searchParams
   *
   * @param string $optionName
   * @return boolean
   */
  public function getSearchParam($key, $default = null) {
    return array_key_exists($key, $this->_searchParams) ? $this->_searchParams[$key] : $default;
  }

  /**
   * nextPage
   *
   * obtain the results for the next page
   * @return DoofinderResults if there are results.
   * @return null otherwise
   */
  public function nextPage() {
    return $this->hasNextPage() ? $this->search($this->getSearchParam('query', ''), $this->getPage() + 1) : null;
  }

  /**
   * previousPage
   *
   * obtain results for the previous page
   * @return DoofinderResults
   * @return null otherwise
   */
  public function previousPage() {
    return $this->hasPreviousPage() ? $this->search($this->getSearchParam('query', ''), $this->getPage() - 1) : null;
  }

  /**
   * getLastPage
   *
   * @return integer the number of pages
   */
  public function getLastPage() {
    return ceil($this->_total / $this->getRpp());
  }

  public function getRpp() {
    return $this->getSearchParam('rpp', self::DEFAULT_RPP);
  }

  // Stats

  public function createSessionId() {
    $time = time();
    $rand = rand();
    return md5("$time$rand");
  }

  /**
   * initSession
   * Starts a session in doofinder search server
   * @return boolean True if it was successfully registered.
   */
  public function registerSession($sessionId){
    return $this->_request('/stats/init', array('session_id' => $sessionId)) == self::OK;
  }

  /**
   * registerClick
   * Register a click
   *
   * params:
   *
   * - session_id
   * - hashid (included by _request)
   * - dfid or id + datatype
   * - query?
   * - custom_results_id?
   *
   * @param string id id of the product whose link is being clicked
   * @param string datatype
   * @param string query query used to get to those results
   * @return boolean true if it was successfully registered.
   */
  public function registerClick($sessionId, $id, $datatype = null, $query = null, $customResultsId = null) {
    $params = array(
      'session_id' => $sessionId
    );

    if (!is_null($datatype)) {
      $params['id'] = $id;
      $params['datatype'] = $datatype;
    } else {
      $params['dfid'] = $id;
    }

    if (!is_null($query)) {
      $params['query'] = $query;
    }

    if (!is_null($customResultsId)) {
      $params['custom_results_id'] = $customResultsId;
    }

    return $this->_request('/stats/click', $params) === self::OK;
  }

  /**
   * registerCheckout
   * register a checkout
   *
   * params:
   *
   * - session_id
   * - hashid (included by _request)
   *
   * @return boolean true if it was successfully registered.
   */
  public function registerCheckout($sessionId){
    return $this->_request('/stats/checkout', array('session_id' => $sessionId)) == self::OK;
  }

  /**
   * registerImageClick
   *
   * params:
   *
   * - session_id
   * - hashid (included by _request)
   * - img_id
   * @param string $bannerId the id of the banner
   * @return boolean true if it was successfully registered.
   */
  public function registerImageClick($sessionId, $imageId){
    return $this->_request('/stats/img_click', array('session_id' => $sessionId, 'img_id' => $imageId)) == self::OK;
  }

  /**
   * registerRedirection
   *
   * params:
   *
   * - session_id
   * - hashid (included by _request)
   * - redirection_id
   * - link
   * - query?
   *
   * @param string $redirectionId the id of the redirection
   * @param string $query the query that led to this redirection
   * @param string $link  the url the redirection points to.
   * @return boolean true if it was successfully registered.
   */
  public function registerRedirection($sessionId, $redirectionId, $link, $query = null) {
    $params = array(
      'session_id' => $sessionId,
      'redirection_id' => $redirectionId,
      'link' => $link
    );

    if (!is_null($query)) {
      $params['query'] = $query;
    }

    return $this->_request('/stats/redirect', $params) == self::OK;
  }

  /**
   * setCustomHeaders
   *
   * adds extra headers to be sent in every request
   * @param array $extraHeaders
   * @return void
   */
  public function setCustomHeaders($headers = array()){
    $this->_customHeaders = $headers;
  }
}
