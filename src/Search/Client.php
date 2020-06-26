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
  const RPP = 10;
  const OK = '"OK"';

  private $_baseUrl = null;
  private $_token = null;

  private $_customHeaders = array();
  private $_searchParams = array();
  private $_lastPage = 0;

  public function __construct($server, $token) {
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

    $searchParams = [];

    foreach ($params as $key => $value) {
      if ($key === $prefix.$queryParameter) {
        $searchParams['query'] = $value;
      } else if ($key = $this->_unprefixParam($key, $prefix)) {
        $searchParams[$key] = $value;
      }
    }

    return $searchParams;
  }

  /**
   * Dump current search params to an assoc array.
   */
  public function dump($options = array()) {
    $serializationOptions = $this->_buildSerializationOptions($options);
    $prefix = $serializationOptions['prefix'];
    $queryParameter = $serializationOptions['queryParameter'];
    $params = [];

    foreach ($this->_searchParams as $key => $value) {
      if ($key === 'query') {
        $params[$prefix.$queryParameter] = $value;
      } else {
        $params[$prefix . $key] = $value;
      }
    }

    if (isset($serializationOptions['page'])) {
      $params[$prefix . 'page'] = $serializationOptions['page'];
    }

    return $params;
  }

  /**
   * qs
   *
   * 'serialize' the object's state to querystring params
   * @param int $page the pagenumber. defaults to the current page
   */
  public function qs($options = array()){
    $params = $this->dump($options);
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
      'hashid', 'query',
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
    $url = $this->_baseUrl.$entryPoint;

    if (count($params)) {
      $params = http_build_query($this->_sanitize($params), '', '&');
      $url .= "?".$params;
    }

    return $url;
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

  public function options($hashid) {
    return $this->_request('/options/'.$hashid);
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
  public function search($searchParams = array()) {
    $params = array_merge_recursive([], $searchParams);

    if (!isset($params['page'])) {
      $params['page'] = 1;
    }

    if (!isset($params['query_name']) && (!isset($params['query']) || !$params['query'])) {
      $params['query_name'] = 'match_all';
    }

    $results = new Results($this->_request('/search', $params));
    $total = $results->getProperty('total');
    $rpp = $results->getProperty('results_per_page');

    $this->_searchParams = array_merge($params, [
      'query_name' => $results->getProperty('query_name')
    ]);
    $this->_lastPage = ceil($total / $rpp);

    return $results;
  }

  /**
   * nextPage
   *
   * obtain the results for the next page
   * @return DoofinderResults if there are results.
   * @return null otherwise
   */
  public function getNextPage() {
    if ($this->_searchParams['page'] < $this->_lastPage) {
      $this->_searchParams['page'] += 1;
      return $this->search($this->_searchParams);
    } else {
      return null;
    }
  }

  /**
   * previousPage
   *
   * obtain results for the previous page
   * @return DoofinderResults
   * @return null otherwise
   */
  public function getPreviousPage() {
    if ($this->_searchParams['page'] > 1) {
      $this->_searchParams['page'] -= 1;
      return $this->search($this->_searchParams);
    } else {
      return null;
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
  public function registerSession($sessionId, $hashid){
    $params = array(
      'session_id' => $sessionId,
      'hashid' => $hashid,
    );
    return $this->_request('/stats/init', $params) == self::OK;
  }

  /**
   * registerClick
   * Register a click
   *
   * params:
   *
   * - session_id
   * - hashid (included by _request)
   * - dfid or id
   *
   * options:
   *
   * - datatype? required if id is not dfid
   * - query?
   * - custom_results_id?
   *
   * @param string id id of the product whose link is being clicked
   * @param string datatype
   * @param string query query used to get to those results
   * @return boolean true if it was successfully registered.
   */
  public function registerClick($sessionId, $hashid, $id, $options = []) {
    $params = array(
      'session_id' => $sessionId,
      'hashid' => $hashid,
    );

    if (isset($options['datatype'])) {
      $params['id'] = $id;
      $params['datatype'] = $options['datatype'];
    } else {
      $params['dfid'] = $id;
    }

    $params = array_merge($params, $options);

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
  public function registerCheckout($sessionId, $hashid) {
    $params = array(
      'session_id' => $sessionId,
      'hashid' => $hashid,
    );
    return $this->_request('/stats/checkout', $params) == self::OK;
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
  public function registerImageClick($sessionId, $hashid, $imageId){
    $params = array(
      'session_id' => $sessionId,
      'hashid' => $hashid,
      'img_id' => $imageId,
    );
    return $this->_request('/stats/img_click', $params) == self::OK;
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
   *
   * options:
   * - query?
   *
   * @param string $redirectionId the id of the redirection
   * @param string $query the query that led to this redirection
   * @param string $link  the url the redirection points to.
   * @return boolean true if it was successfully registered.
   */
  public function registerRedirection($sessionId, $hashid, $redirectionId, $link, $options = []) {
    $params = array_merge([
      'session_id' => $sessionId,
      'hashid' => $hashid,
      'redirection_id' => $redirectionId,
      'link' => $link,
    ], $options);
    return $this->_request('/stats/redirect', $params) == self::OK;
  }

  /**
   * options:
   * - datatype?
   * - custom_results_id?
   */
  public function addToCart($sessionId, $hashid, $itemId, $amount, $options = []) {
    $params = array_merge([
      'session_id' => $sessionId,
      'hashid' => $hashid,
      'item_id' => $itemId,
      'amount' => $amount
    ], $options);
    return $this->_request('/stats/add-to-cart', $params) == self::OK;
  }

  /**
   * options:
   * - datatype
   * - custom_results_id
   */
  public function removeFromCart($sessionId, $hashid, $itemId, $amount, $options = []) {
    $params = array_merge([
      'session_id' => $sessionId,
      'hashid' => $hashid,
      'item_id' => $itemId,
      'amount' => $amount
    ], $options);
    return $this->_request('/stats/remove-from-cart', $params) == self::OK;
  }

  public function clearCart($sessionId, $hashid) {
    $params = [
      'session_id' => $sessionId,
      'hashid' => $hashid,
    ];
    return $this->_request('/stats/clear-cart', $params) == self::OK;
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
