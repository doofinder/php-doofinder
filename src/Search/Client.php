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

  /**
   * Create instances of this class.
   *
   * @param string $server - Search server name.
   *                         i.e.: eu1-search.doofinder.com
   * @param string $token  - API Token.
   *                         i.e: 76d48dfff9b014f3a28da2382a5bd98b7276445d
   */
  public function __construct($server, $token) {
    if (!preg_match('/^https?:/', $server)) {
      $this->_baseUrl = sprintf('https://%s/5', $server);
    } else {
      $this->_baseUrl = sprintf('%s/5', $server);
    }
    $this->_token = $token;
  }

  /**
   * Convert dumped parameters to real parameters.
   *
   * Available options are:
   *
   * - prefix: If provided, will be removed from the name of each param.
   * - queryParameter: If provided, will detect the query parameter using
   *   this value instead of the default 'query'. Must be unprefixed.
   *
   * @param array $params   - Search parameters from $_REQUEST, $_GET or $_POST.
   * @param array $options  - Options array.
   *
   * @return Array - Array of parameters to be used for search.
   */
  public function searchParams($params, $options = array()) {
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
   *
   * Available options are:
   *
   * - prefix: If provided, will be removed from the name of each param.
   * - queryParameter: If provided, will dump the query parameter using
   *   this value instead of the default 'query'. Must be unprefixed.
   *
   * @param array $options  - Options array.
   *
   * @return Array - Array of prefixed parameters.
   */
  public function dumpParams($options = array()) {
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
   * Serialize current search parameters to string.
   *
   * Available options are:
   *
   * - prefix: If provided, will be removed from the name of each param.
   * - queryParameter: If provided, will dump the query parameter using
   *   this value instead of the default 'query'. Must be unprefixed.
   *
   * @param array $options  - Options array.
   *
   * @return string - A string representing the search parameters.
   */
  public function qs($options = array()){
    $params = $this->dumpParams($options);
    if (isset($options["page"])) {
      $params["page"] = $options["page"];
    }
    return http_build_query($params, '', '&');
  }

  /**
   * Perform a search request.
   *
   * More information about search parameters here:
   *
   * https://www.doofinder.com/support/developer/api/search-api
   *
   * @example
   *
   * $client->search([
   *   'hashid' => '0123456789abcdef0123456789abcdef',
   *   'query' => 'something',
   *   'filter' => [
   *     'color' => ['red', 'green'],
   *     'price' => [
   *       'gte' => 10,
   *       'lte' => 50
   *     ]
   *   ]
   * ]);
   *
   * @param array $params - Search parameters as an associative array.
   *
   * @return Results
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
   * Get the next set of results from the last search.
   *
   * @return Results
   * @return null
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
   * Get the previous set of results from the last search.
   *
   * @return Results
   * @return null
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
   * Get a search parameter value from the client status.
   *
   * @param string $key     - Parameter name.
   * @param string $default - Default value if the parameter does not exist.
   * @return mixed
   */
  public function getSearchParam($key, $default = null) {
    return array_key_exists($key, $this->_searchParams) ? $this->_searchParams[$key] : $default;
  }

  public function getTotalPages() {
    return $this->_lastPage;
  }

  /**
   * Generate a hash to be used as a session id.
   *
   * @return string
   */
  public function createSessionId() {
    $time = time();
    $rand = rand();
    return md5("$time$rand");
  }

  /**
   * Register a session id in Doofinder.
   *
   * @param string $sessionId
   * @param string $hashid
   *
   * @return boolean
   */
  public function registerSession($sessionId, $hashid){
    $params = array(
      'session_id' => $sessionId,
      'hashid' => $hashid,
    );
    return $this->_request('/stats/init', $params) == self::OK;
  }

  /**
   * Register a click on a result in Doofinder.
   *
   * Available options are:
   *
   * - datatype: If provided, the $id parameter is considered the id of
   *             the item in your database.
   * - query: The search terms used to find the item being registered.
   * - custom_results_id: The id of the custom result that matched the
   *                      query.
   *
   * @param string  $sessionId
   * @param string  $hashid
   * @param string  $id - The "doofinder id" associated to the item. If a
   *                      datatype is provided via $options, this will
   *                      represent the "id" provided for the item when
   *                      indexed.
   * @param array   $options
   *
   * @return boolean
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
   * Register a checkout for the provided session id and hashid.
   *
   * @param string  $sessionId
   * @param string  $hashid
   *
   * @return boolean
   */
  public function registerCheckout($sessionId, $hashid) {
    $params = array(
      'session_id' => $sessionId,
      'hashid' => $hashid,
    );
    return $this->_request('/stats/checkout', $params) == self::OK;
  }

  /**
   * Register a click on a banner image.
   *
   * @param string  $sessionId
   * @param string  $hashid
   * @param string  $img_id - Id of the image (from the search response).
   *
   * @return boolean
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
   * Register a redirection.
   *
   * Available options are:
   *
   * - query: the search terms that made the redirection match.
   *
   * @param string  $sessionId
   * @param string  $hashid
   * @param string  $redirectionId - Id of the redirecton (from search response).
   * @param string  $link - Target link.
   * @param array   $options
   *
   * @return boolean
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
   * Adds an amount of item to the cart in the current session.
   *
   * The cart will be automatically stored in stats if there's any call
   * to registerCheckout. If the item is already in the cart, the amount
   * is automatically added to the current amount.
   *
   * Available options are:
   *
   * - datatype: If provided, the $id parameter is considered the id of
   *             the item in your database.
   * - custom_results_id: The id of the custom result that matched the
   *                      query.
   *
   * @param string  $sessionId
   * @param string  $hashid
   * @param string  $id - The "doofinder id" associated to the item. If a
   *                      datatype is provided via $options, this will
   *                      represent the "id" provided for the item when
   *                      indexed.
   * @param string  $amount - number of items to add.
   * @param array   $options
   *
   * @return boolean
   */
  public function addToCart($sessionId, $hashid, $id, $amount, $options = []) {
    $params = array_merge([
      'session_id' => $sessionId,
      'hashid' => $hashid,
      'item_id' => $id,
      'amount' => $amount
    ], $options);
    return $this->_request('/stats/add-to-cart', $params) == self::OK;
  }

  /**
   * Removes an amount of item from the cart in the current session.
   *
   * The cart will be automatically stored in stats if there's any call
   * to registerCheckout. If any of the items' amount drops to zero or
   * below, it is automatically removed from the cart
   *
   * Available options are:
   *
   * - datatype: If provided, the $id parameter is considered the id of
   *             the item in your database.
   * - custom_results_id: The id of the custom result that matched the
   *                      query.
   *
   * @param string  $sessionId
   * @param string  $hashid
   * @param string  $id - The "doofinder id" associated to the item. If a
   *                      datatype is provided via $options, this will
   *                      represent the "id" provided for the item when
   *                      indexed.
   * @param string  $amount - number of items to remove.
   * @param array   $options
   *
   * @return boolean
   */
  public function removeFromCart($sessionId, $hashid, $id, $amount, $options = []) {
    $params = array_merge([
      'session_id' => $sessionId,
      'hashid' => $hashid,
      'item_id' => $id,
      'amount' => $amount
    ], $options);
    return $this->_request('/stats/remove-from-cart', $params) == self::OK;
  }

  /**
   * Clears the cart in the current session.
   *
   * @param string  $sessionId
   * @param string  $hashid
   *
   * @return boolean
   */
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

  //
  // Private
  //

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
}
