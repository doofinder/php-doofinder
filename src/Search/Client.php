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

namespace Doofinder\Api\Search;

use Doofinder\Api\Search\Results;
use Doofinder\Api\Search\Error;

/**
 * Doofinder\Api\Client
 *
 * Basic client to perform requests to Doofinder search servers.
 */
class Client {
  const URL_SUFFIX = '-search.doofinder.com';
  const DEFAULT_TIMEOUT = 10000;
  const DEFAULT_RPP = 10;
  const DEFAULT_PARAMS_PREFIX = 'dfParam_';
  const DEFAULT_API_VERSION = '5';
  const VERSION = '5.4.3';

  private $api_key = null; // Authentication
  private $hashid = null;  // ID of the Search Engine

  private $apiVersion = null;
  private $url = null;
  private $results = null;
  private $query = null;
  private $search_options = array(); // Assoc. array of request parameters

  private $page = 1;          // Current "page" of results
  private $queryName = null;  // Last successful query name
  private $lastQuery = null;  // Last successful query made
  private $total = null;      // Total number of results
  private $maxScore = null;
  private $paramsPrefix = self::DEFAULT_PARAMS_PREFIX;
  private $serializationArray = null;
  private $queryParameter = 'query';
  private $allowedParameters = array('page', 'rpp', 'timeout', 'types',
                                     'filter', 'query_name', 'transformer',
                                     'sort'); // Valid parameters

  /**
   * Constructor.
   *
   * @param string  $hashid       Search Engine's hashid.
   * @param boolean $fromParams   If set, the object is unserialized from GET
   *                              or POST params.
   * @param array   $init_options Associative array with some options:
   *
   *   -'prefix'             (default: 'dfParam_') Prefix to use when serializing.
   *   -'queryParameter'     (default: 'query')    Parameter used for querying.
   *   -'apiVersion'         (default: '5')        Version of the API to use.
   *                                               Valid versions: 5, 4, 3.0, 1.0
   *   -'restrictedRequest'  (default: null)       Can be 'get' or 'post'. If defined, restricts
   *                                               the request object to either $_GET or $_POST
   *                                               when unserializing to get parameters.
   *
   * @throws Error if $hashid is not a md5 hash or API version is not valid.
   */
  public function __construct($hashid, $api_key, $fromParams = false, $init_options = array()) {
    $zone_key_array = explode('-', $api_key);

    if (2 === count($zone_key_array)) {
      $this->api_key = $zone_key_array[1];
      $this->zone = $zone_key_array[0];
      $this->url = "https://" . $this->zone . self::URL_SUFFIX;
    } else {
      throw new Error("API Key is no properly set.");
    }

    if (array_key_exists('prefix', $init_options)) {
      $this->paramsPrefix = $init_options['prefix'];
    }

    $this->allowedParameters = array_map(array($this, 'addPrefix'), $this->allowedParameters);

    if (array_key_exists('queryParameter', $init_options)) {
      $this->queryParameter = $init_options['queryParameter'];
    } else {
      $this->queryParameter = $this->paramsPrefix.$this->queryParameter;
    }

    if (array_key_exists('apiVersion', $init_options)) {
      $this->setApiVersion($init_options['apiVersion']);
    } else {
      $this->setApiVersion(self::DEFAULT_API_VERSION);
    }

    if (array_key_exists('restrictedRequest', $init_options)) {
      switch(strtolower($init_options['restrictedRequest'])) {
        case 'get':
          $this->serializationArray = $_GET;
          break;
        case 'post':
          $this->serializationArray = $_POST;
          break;
        default:
          throw new Error("Wrong initialization value for 'restrictedRequest'");
      }
    } else {
      $this->serializationArray = $_REQUEST;
    }

    if (!preg_match('/^[0-9a-f]{32}$/i', $hashid)) {
        throw new Error("Wrong hashid");
    }

    $this->hashid = $hashid;

    if ($fromParams) {
      $this->fromQuerystring();
    }
  }

  private function addPrefix($value) {
    return $this->paramsPrefix.$value;
  }

  private function getRequestHeaders(){
    $headers = array();
    $headers[] = 'Expect:'; // Fixes HTTP/1.1 "417 Expectation Failed" Error
    if ($this->authenticationHeader !== false) {
      $headers[] = sprintf("%s: %s", $this->authenticationHeader, $this->api_key);
    }
    return $headers;
  }

  private function apiCall($entry_point = 'search', $params = array()){
    $params['hashid'] = $this->hashid;

    $session = curl_init(sprintf("%s/%s/%s?%s", $this->url,
                                                $this->apiVersion,
                                                $entry_point,
                                                http_build_query($this->sanitize($params))));

    // Configure cURL to return response but not headers
    curl_setopt($session, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_HTTPHEADER, $this->getRequestHeaders());

    $response = curl_exec($session);
    $statusCode = curl_getinfo($session, CURLINFO_HTTP_CODE);

    curl_close($session);

    if (floor($statusCode / 100) == 2) {
        return $response;
    }

    throw new Error($statusCode.' - '.$response, $statusCode);
  }

  public function getOptions() {
    return $this->apiCall('options/'.$this->hashid);
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
   *        - any other param will be sent as a request parameter
   * @return DoofinderResults results
   */
  public function query($query = null, $page = null, $options = array()) {
    if ($query) {
      $this->search_options['query'] = $query;
    }

    if ($page) {
      $this->search_options['page'] = intval($page);
    }

    foreach ($options as $optionName => $optionValue) {
      $this->search_options[$optionName] = $options[$optionName];
    }

    $params = $this->search_options;

    // translate filters
    if(!empty($params['filter']))
    {
      foreach($params['filter'] as $filterName => $filterValue){
        $params['filter'][$filterName] = $this->updateFilter($filterValue);
      }
    }

    // no query? then match all documents
    if(!$this->optionExists('query') || !trim($this->search_options['query'])){
      $params['query_name'] = 'match_all';
    }

    // if filters without query_name, pre-query first to obtain it.
    if (empty($params['query_name']) && !empty($params['filter']))
    {
      $filter = $params['filter'];
      unset($params['filter']);
      $dfResults = new Results($this->apiCall('search', $params));
      $params['query_name'] = $dfResults->getProperty('query_name');
      $params['filter'] = $filter;
    }
    $dfResults = new Results($this->apiCall('search', $params));
    $this->page = $dfResults->getProperty('page');
    $this->total = $dfResults->getProperty('total');
    $this->search_options['query'] = $dfResults->getProperty('query');
    $this->maxScore = $dfResults->getProperty('max_score');
    $this->queryName = $dfResults->getProperty('query_name');
    $this->lastQuery = $dfResults->getProperty('query');

    return $dfResults;
  }

  /**
   * hasNext
   *
   * @return boolean true if there is another page of results
   */
  public function hasNext(){
    return $this->page * $this->getRpp() < $this->total;
  }

  /**
   * hasPrev
   *
   * @return true if there is a previous page of results
   */
  public function hasPrev(){
    return ($this->page - 1) * $this->getRpp() > 0;
  }


  /**
   * getPage
   *
   * obtain the current page number
   * @return int the page number
   */
  public function getPage(){
    return $this->page;
  }

  /**
   * setFilter
   *
   * set a filter for the query
   * @param string filterName the name of the filter to set
   * @param array filter if simple array, terms filter assumed
   *                     if 'from', 'to' in keys, range filter assumed
   */
  public function setFilter($filterName, $filter){
    $this->search_options['filter'][$filterName] = (array) $filter;
  }

  /**
   * getFilter
   *
   * get conditions for certain filter
   * @param string filterName
   * @return array filter conditions: - simple array if terms filter
   *                                  - 'from', 'to'  assoc array if range f.
   * @return false if no filter definition found
   */
  public function getFilter($filterName){
    if (isset($this->search_options['filter'][$filterName])) {
      return $this->search_options['filter'][$filterName];
    }

    return false;
  }

  /**
   * getFilters
   *
   * get all filters and their configs
   * @return array assoc array filterName => filterConditions
   */
  public function getFilters() {
    if (isset($this->search_options['filter'])) {
      return $this->search_options['filter'];
    }

    return array();
  }

  /**
   * addTerm
   *
   * add a term to a terms filter
   * @param string filterName the filter to add the term to
   * @param string term the term to add
   */
  public function addTerm($filterName, $term) {
    $this->search_options['filter'][$filterName][] = $term;
  }

  /**
   * removeTerm
   *
   * remove a term from a terms filter
   * @param string filterName the filter to remove the term from
   * @param string term the term to be removed
   */
  public function removeTerm($filterName, $term) {
    if (isset($this->search_options['filter'][$filterName])) {
      $idx = array_search($term, $this->search_options['filter'][$filterName]);
      if ($idx !== false) {
        array_splice($this->search_options['filter'][$filterName], $idx, 1);
      }
    }
  }

  /**
   * setRange
   *
   * set a range filter
   * @param string filterName the filter to set
   * @param int from the lower bound value. included
   * @param int to the upper bound value. included
   */
  public function setRange($filterName, $from = null, $to = null) {
    if (!is_null($from))
    {
      $this->search_options['filter'][$filterName]['from'] = $from;
    }
    if (!is_null($to))
    {
      $this->search_options['filter'][$filterName]['to'] = $to;
    }
  }

  /**
   * addSort
   *
   * Tells doofinder to sort results, or add a new field to a multiple fields sort
   * @param string fieldName the field to sort by
   * @param string direction 'asc' o 'desc'.
   */
  public function addSort($sortName, $direction) {
    $this->search_options['sort'][] = array($sortName => $direction);
  }

  /**
   * toQuerystring
   *
   * 'serialize' the object's state to querystring params
   * @param int $page the pagenumber. defaults to the current page
   */
  public function toQuerystring($page = null){
    $toParams = array();

    foreach ($this->search_options as $paramName => $paramValue) {
      if ($paramName == 'query') {
        $toParams[$this->queryParameter] = $paramValue;
      } else {
        $toParams[$this->paramsPrefix.$paramName] = $paramValue;
      }
    }

    if (!is_null($page)) {
      $toParams[$this->paramsPrefix.'page'] = $page;
    }

    return http_build_query($toParams);
  }

  /**
   * fromQuerystring
   *
   * obtain object's state from querystring params
   * @param string $params  where to obtain params from:
   *                       - 'GET' $_GET params (default)
   *                       - 'POST' $_POST params
   */
  public function fromQuerystring(){
    $filteredParams = array_filter(array_keys($this->serializationArray),
                                   array($this, 'belongsToDoofinder'));

    foreach ($filteredParams as $param) {
      if ($param == $this->queryParameter) {
        $key = 'query';
      } else {
        $key = substr($param, strlen($this->paramsPrefix));
      }

      $this->search_options[$key] = $this->serializationArray[$param];
    }
  }

  /**
   * Ensures that range filters uses the most up to date syntax.
   *
   *    array('from' => 9, 'to' => 20, 'other': 10)
   *
   * is converted into:
   *
   *    array('gte' => 9, 'lte' => 20, 'other': 10)
   *
   * @param  array $filter Filter definition.
   * @return array         Updated filter.
   */
  private function updateFilter($filter) {
    $new_filter = array();

    foreach($filter as $key => $value) {
      if ($key === 'from') {
        $new_filter['gte'] = $value;
      } else if ($key === 'to') {
        $new_filter['lte'] = $value;
      } else {
        $new_filter[$key] = $value;
      }
    }

    return $new_filter;
  }

  /**
   * Cleans $params array by removing keys with empty/null values.
   *
   * @param   array $params Array to be cleaned.
   * @return  array         Array with no empty keys.
   */
  private function sanitize($params) {
    $result = array();

    foreach ($params as $name => $value) {
      if (is_array($value)) {
        $result[$name] = $this->sanitize($value);
      } else if (trim($value)) {
        $result[$name] = $value;
      }
    }

    return $result;
  }

  /**
   * belongsToDoofinder
   *
   * to know if certain parameter name belongs to doofinder serialization parameters
   *
   * @param string $paramName name of the param
   * @return boolean true or false.
   */
  private function belongsToDoofinder($paramName){
    if ($pos = strpos($paramName, '[')) {
      $paramName = substr($paramName, 0, $pos);
    }

    return in_array($paramName, $this->allowedParameters) || $paramName == $this->queryParameter;
  }

  /**
   * optionExists
   *
   * checks whether a search option is defined in $this->search_options
   *
   * @param string $optionName
   * @return boolean
   */
  private function optionExists($optionName) {
    return array_key_exists($optionName, $this->search_options);
  }

  /**
   * nextPage
   *
   * obtain the results for the next page
   * @return DoofinderResults if there are results.
   * @return null otherwise
   */
  public function nextPage() {
    return $this->hasNext() ? $this->query($this->lastQuery, $this->page + 1) : null;
  }


  /**
   * prevPage
   *
   * obtain results for the previous page
   * @return DoofinderResults
   * @return null otherwise
   */
  public function prevPage() {
    return $this->hasPrev() ? $this->query($this->lastQuery, $this->page - 1) : null;
  }

  /**
   * numPages
   *
   * @return integer the number of pages
   */
  public function numPages() {
    return ceil($this->total / $this->getRpp());
  }

  public function getRpp() {
    $rpp = $this->optionExists('rpp') ? $this->search_options['rpp'] : null;
    return $rpp ? $rpp : self::DEFAULT_RPP;
  }

  /**
   * setApiVersion
   *
   * sets the api version to use.
   * @param string $apiVersion the api version , '1.0', '3.0', '4' or '5'
   */
  public function setApiVersion($apiVersion) {
    switch (true) {
      case in_array($apiVersion, array('1.0', '3.0')):
        $this->authenticationHeader = false;
        break;
      case intval($apiVersion) == 4:
        $this->authenticationHeader = 'API Token';
        break;
      case intval($apiVersion) == 5:
        $this->authenticationHeader = 'Authorization';
        break;
      default:
        throw new Error('Wrong API Version');
    }

    $this->apiVersion = $apiVersion;
  }

  /**
   * setPrefix
   *
   * sets the prefix that will be used for serialization to querystring params
   * @param string $prefix the prefix
   */
  public function setPrefix($prefix) {
    $this->paramsPrefix = $prefix;
  }

  /**
   * getFilterType
   * obtain the filter type (i.e. 'terms' or 'numeric range' from its conditions)
   * @param array filter conditions
   * @return string 'terms' or 'numericrange' false otherwise
   */
  private function getFilterType($filter) {
    if (is_array($filter)) {
      if (array_key_exists('from', $filter) || array_key_exists('to', $filter)) {
        return 'numericrange';
      } else {
        return 'term';
      }
    }
    return false;
  }
}
