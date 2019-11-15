<?php
/**
 * Author:: JoeZ99 (<jzarate@gmail.com>).
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

namespace Doofinder\Api\Management;

use Doofinder\Api\Management\SearchEngine;
use Doofinder\Api\Management\Errors\Utils;
use Doofinder\Api\Management\Errors\InvalidApiKey;
use Doofinder\Api\Management\Errors\BadRequest;

/**
 * Class to manage the connection with the API servers.
 *
 * Needs APIKEY in initialization.
 * Example: $dma = new DoofinderManagementApi('eu1-d531af87f10969f90792a4296e2784b089b8a875')
 */
class Client
{
  const REMOTE_API_ENDPOINT = "https://%s-api.doofinder.com/v1";
  const LOCAL_API_ENDPOINT = "http://localhost:8000/api/v1";

  private $apiKey = null;
  private $clusterRegion = 'eu1';
  private $token = null;
  private $baseManagementUrl = null;

  public function __construct($apiKey, $local = false) {
    $this->apiKey = $apiKey;
    $clusterToken = explode('-', $apiKey);
    if(count($clusterToken) != 2){
        throw new InvalidApiKey("Invalid API Key provided");
    }
    $this->clusterRegion = $clusterToken[0];
    $this->token = $clusterToken[1];

    if ($local === true){
      $this->baseManagementUrl = self::LOCAL_API_ENDPOINT;
    } else {
      $this->baseManagementUrl = sprintf(self::REMOTE_API_ENDPOINT, $this->clusterRegion);
    }
  }

  /**
   * Makes the actual request to the API server and normalize response
   *
   * @param string $method The HTTP method to use. 'GET|PUT|POST|DELETE'
   * @param string $entryPoint The path to use. '/<hashid>/items/product'
   * @param array $params If any, url request parameters
   * @param array $data If any, body request parameters
   * @return array Array with both status code and response .
   */
  public function managementApiCall($method = 'GET', $entryPoint = '', $params = null, $data = null) {
    $headers = array(
      'Authorization: Token ' . $this->token,
      'Content-Type: application/json',
      'Expect:', // Fixes the HTTP/1.1 417 Expectation Failed error
    );

    $url = $this->baseManagementUrl.'/'.$entryPoint;

    if(is_array($params) && sizeof($params) > 0){
        $url .= '?'.http_build_query($params, '', '&');
    }

    if (!in_array($method, array('POST', 'PUT', 'PATCH', 'DELETE'))){
      $data = null;
    }

    $serverResponse = $this->talkToServer($method, $url, $headers, $data);
    $statusCode = $serverResponse['statusCode'];
    $contentResponse = $serverResponse['contentResponse'];

    $error = Utils::handleErrors($statusCode, $contentResponse);

    if ($error) {
      throw $error;
    }

    return array(
      'statusCode' => $statusCode,
      'response' => ($decoded = json_decode($contentResponse, true)) ? $decoded : $contentResponse
    );
  }

  /**
   * To get info on all possible api entry points
   * @return array An assoc. array with the different entry points
   */
  public function getApiRoot() {
    $response = $this->managementApiCall();
    return $response['response'];
  }

  /**
   * Obtain a list of SearchEngines objects, ready to interact with the API
   * @return array list of searchEngines objects
   */
  public function getSearchEngines(){
    $searchEngines = array();
    $keep = true;
    $page = 1;
    // keep asking for search engines as long as there's a "next" link
    while($keep){
      $response = $this->managementApiCall('GET', 'searchengines', array('page'=>$page));
      $searchEngines = array_merge(
        $searchEngines, $this->buildSearchEngines($response['response']['results'])
      );
      $keep = $response['response']['next'] != NULL;
      $page++;
      if ($keep) {
        // Guarantee max two queries per second
        // @codingStandardsIgnoreLine
        usleep(600000);
      }
    }

    return $searchEngines;
  }

  /**
   * Obtain a single SearchEngine object, ready to interact with the API
   *
   * @param string $hashid hashid of the searchEngine to fetch
   * @return SearchEngine searchEngine object
   */
  public function getSearchEngine($hashid){
    $response = $this->managementApiCall('GET', "searchengines/{$hashid}");
    $searchengine_options = $response['response'];
    return new SearchEngine(
        $this, $searchengine_options['hashid'], $searchengine_options['name'], $searchengine_options
    );
  }

  /**
   * Creates a new SearchEngine
   *
   * @param string $name Name of the searchEngine. Required
   * @param array $options Assoc array with all properties for the created search engine
   * @return SearchEngine the created SearchEngine object
   */
  public function addSearchEngine($name, $options = array()){
    $valid_fields = array('name', 'site_url', 'language', 'currency');
    $payload = array_merge(array('name' => $name), $options);
    $foreign_fields = array_diff(array_keys($payload), $valid_fields);

    if(count(($foreign_fields))){
      $foreign_fields = json_encode(array_values($foreign_fields));
      throw new BadRequest("The fields {$foreign_fields} are not allowed");
    }
    $result = $this->managementApiCall('POST', 'searchengines', null, json_encode($payload));

    return $this->buildSearchEngine($result['response']);
  }

  /**
   * Delete a  SearchEngine
   *
   * @param string $hashid hashid of the searchEngine. Required
   * @return boolean true on success
   */
  public function deleteSearchEngine($hashid){
    $result = $this->managementApiCall('DELETE', "searchengines/{$hashid}");

    return $result['statusCode'] == 204; // dont know why it isn't 202
  }

  /**
   * Updates a SearchEngine
   *
   * @param string $hashid hashid of the SearchEngine to be updated. Required
   * @param array $options Assoc array with the options to update
   *
   * @return SearchEngine the updated searchEngine
   */
  public function updateSearchEngine($hashid, $options = array()) {
    $valid_fields = array('name', 'site_url', 'language', 'currency');
    $foreign_fields = array_diff(array_keys($options), $valid_fields);

    if(count($foreign_fields)){
      $foreign_fields = json_encode(array_values($foreign_fields));
      throw new BadRequest("The fields {$foreign_fields} are not allowed");
    }

    $result = $this->managementApiCall('PATCH', "searchengines/{$hashid}", null, json_encode($options));

    return $this->buildSearchEngine($result['response']);
  }

  protected function talkToServer($method, $url, $headers, $data)
  {
    $session = curl_init($url);
    curl_setopt($session, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true); // Tell curl to return the response
    curl_setopt($session, CURLOPT_HTTPHEADER, $headers); // Adding request headers

    if (!is_null($data)) {
      $data = is_array($data) ? json_encode($data): $data; // just for safety
      curl_setopt($session, CURLOPT_POSTFIELDS, $data);
    }

    $contentResponse = curl_exec($session);
    $statusCode = curl_getinfo($session, CURLINFO_HTTP_CODE);

    curl_close($session);

    return array(
      'contentResponse' => $contentResponse,
      'statusCode' => $statusCode
    );

  }

  /**
   * Builds a list of searchEngines from a list of raw assoc arrays
   * @param array list of searchEngines attribues
   * @return array list of searchengines
   */
  private function buildSearchEngines($searchEnginesListing){
    $searchEngines = array();
    foreach ($searchEnginesListing as $searchengine_options) {
      $searchEngines[] = new SearchEngine(
        $this, $searchengine_options['hashid'], $searchengine_options['name'], $searchengine_options
      );
    }
    return $searchEngines;
  }

  /**
   * Builds a SearchEngine object from assoc. array
   * it hopes to find 'name', 'site_url', 'currency' and 'language' keys
   *
   * @param array $attributes attrs to build the searchengine with
   * @return SearchEngine
   */
  private function buildSearchEngine($attributes){
    return new SearchEngine($this, $attributes['hashid'], $attributes['name'], $attributes);
  }

}
