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

    if (is_array($params) && sizeof($params) > 0) {
      $url .= '?'.http_build_query($params);
    }

    $session = curl_init($url);
    curl_setopt($session, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true); // Tell curl to return the response
    curl_setopt($session, CURLOPT_HTTPHEADER, $headers); // Adding request headers

    if (in_array($method, array('POST', 'PUT'))) {
      curl_setopt($session, CURLOPT_POSTFIELDS, $data);
    }

    $response = curl_exec($session);
    $httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
    curl_close($session);

    $error = Utils::handleErrors($httpCode, $response);

    if ($error) {
      throw $error;
    }

    return array(
      'statusCode' => $httpCode,
      'response' => ($decoded = json_decode($response, true)) ? $decoded : $response,
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
    $apiRoot = $this->getApiRoot();
    unset($apiRoot['searchengines']);

    foreach ($apiRoot as $hashid => $props) {
      $searchEngines[] = new SearchEngine($this, $hashid, $props['name']);
    }

    return $searchEngines;
  }
}
