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
require_once dirname(__FILE__).'/errors.php';



class DoofinderManagementApi{
    /**
     * Class to manage the connection with the API servers.
     *
     * Needs APIKEY in initialization.
     * Example: $dma = new DoofinderManagementApi('eu1-d531af87f10969f90792a4296e2784b089b8a875')
     */

    const MANAGEMENT_DOMAIN_SUFFIX = '-api.doofinder.com';
    const MANAGEMENT_VERSION = 1;

    private $apiKey = null;
    private $clusterRegion = 'eu1';
    private $token = null;
    private $baseManagementUrl = null;

    function __construct($apiKey){
        $this->apiKey = $apiKey;
        $clusterToken = explode('-', $apiKey);
        $this->clusterRegion = $clusterToken[0];
        $this->token = $clusterToken[1];
        $this->baseManagementUrl = "https://" . $this->clusterRegion . self::MANAGEMENT_DOMAIN_SUFFIX . "/v" . self::MANAGEMENT_VERSION;
        // $this->baseManagementUrl = 'localhost:8000/api/v1';
    }

    function managementApiCall($method='GET', $entryPoint='', $params=null, $data=null){
        /**
         * Makes the actual request to the API server and normalize response
         *
         * @param string $method The HTTP method to use. 'GET|PUT|POST|DELETE'
         * @param string $entryPoint The path to use. '/<hashid>/items/product'
         * @param array $params If any, url request parameters
         * @param array $data If any, body request parameters
         * @return array Array with both status code and response .
         */
        $headers = array('Authorization: Token '.$this->token, // for Auth
                         'Content-Type: application/json',
                         'Expect:'); // Fixes the HTTP/1.1 417 Expectation Failed

        $fullUrl = $this->baseManagementUrl.'/'.$entryPoint;
        if(is_array($params) && sizeof($params) > 0){
            $fullUrl .= '?'.http_build_query($params);
        }
        $session = curl_init($fullUrl);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true); // Tell curl to return the response
        curl_setopt($session, CURLOPT_HTTPHEADER, $headers); // Adding request headers
        if(in_array($method, array('POST', 'PUT'))){
            curl_setopt($session, CURLOPT_POSTFIELDS, $data);
        }
        $response = curl_exec($session);
        $httpCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
        curl_close($session);

        handleErrors($httpCode, $response);

        $return = array('statusCode' => $httpCode);
        $return['response'] = ($decoded = json_decode($response, true)) ? $decoded : $response;

        return $return;
    }

    function getApiRoot() {
        /**
         * To get info on all possible api entry points
         * @return array An assoc. array with the different entry points
         */
        return $this->managementApiCall()['response'];
    }

    function getSearchEngines() {
        /**
         * Obtain a list of SearchEngines objects, ready to interact with the API
         * @return array list of searchEngines objects
         */
        $searchEngines = array();
        $apiRoot = $this->getApiRoot();
        unset($apiRoot['searchengines']);
        foreach($apiRoot as $hashid => $props){
            $searchEngines[] = new SearchEngine($this, $hashid, $props['name']);
        }
        return $searchEngines;
    }

}

class SearchEngine {
    /**
     * Class with all the capabilities described in the API
     *
     * see http://www.doofinder.com/developer/topics/api/management-api
     */

    public $name = null;
    public $hashid = null;
    private $dma = null; // DoofinderManagementApi instance

    function __construct($dma, $hashid, $name) {
        $this->name = $name;
        $this->hashid = $hashid;
        $this->dma = $dma;
    }

    function getDatatypes(){
        /**
         * Get a list of searchengine's types
         *
         * @return array list of types
         */
        return $this->getTypes();
    }

    function getTypes() {
        /**
         * Get a list of searchengine's types
         *
         * @return array list of types
         */
        $result = $this->dma->managementApiCall('GET', $this->hashid.'/types');
        return $result['response'];
    }

    function addType($dType) {
        /**
         * Add a type to the searchengine
         *
         * @param string $dType the type name
         * @return new list of searchengine's types
         */
        $result = $this->dma->managementApiCall(
            'POST', $this->hashid.'/types', null, json_encode(array('name'=>$dType))
        );
        return $result['response'];
    }

    function deleteType($dType) {
        /**
         * Delete a type and all its items. HANDLE WITH CARE
         *
         * @param string $dType the Type to delete. All items belonging
         *                          to that type will be removed. mandatory
         * @return boolean true on success
         */
        $result = $this->dma->managementApiCall(
            'DELETE', $this->hashid.'/types/'.$dType
        );
        return $result['statusCode'] == 204;
    }

    public function getScrolledItemsPage($dType, $scrollId = null) {
        /**
         * Get paginated indexed items belonging to a searchengine's type
         *
         * It only paginates forward. Can't go backwards
         * @param string $dType Type of the items to list
         * @param string $scrollId identifier of the pagination set
         * @return array Assoc array with scroll_id ,paginated results and total results.
         */

        $params = $scrollId ? array("scroll_id"=>$scrollId) : null;

        $result = $this->dma->managementApiCall(
            'GET', $this->hashid.'/items/'.$dType,
            $params
        );

        return array(
            'scroll_id' => $result['response']['scroll_id'],
            'results' => $result['response']['results'],
            'total' => $result['response']['count']
        );
    }

    function items($dType){
        return new ItemsRS($this, $dType);
    }

    function getItem($dType, $itemId) {
        /**
         * Get details of a specific item
         *
         * @param string $dType Type of the item.
         * @param string $itemId the id of the item
         * @return array Assoc array representing the item.
         */
        $result = $this->dma->managementApiCall(
            'GET', $this->hashid."/items/$dType/$itemId");
        return $result['response'];
    }

    function addItem($dType, $itemDescription){
        /**
         * Add an item to the search engine
         *
         *   - If the 'id' field is present, use that as item's id or overwrite an existing
         *     item with that id.
         *   - It the 'id' field is not present, create one.
         *
         * @param string $dType type of the. If not provided, first available type is used
         * @param array $itemDescription Assoc array representation of the item
         * @return string the id of the item just created
         */
        $result = $this->dma->managementApiCall(
            'POST', $this->hashid."/items/$dType", null, json_encode($itemDescription)
        );
        return $result['response']['id'];
    }

    function addItems($dType, $itemsDescription){
        /**
         * Add items in bulk to the search engine
         *
         * For each item:
         *   - If the 'id' field is present, use that as item's id or overwrite an existing
         *     item with that id.
         *   - It the 'id' field is not present, create one.
         *
         * @param string $dType type of the. If not provided, first available type is used
         * @param array $itemsDescription List of Assoc array representation of the item
         * @return array List of ids of the added items
         */
        $result = $this->dma->managementApiCall(
            'POST', $this->hashid."/items/$dType", null, json_encode($itemsDescription)
        );
        function fetchId($item){
            return $item['id'];
        };
        return array_map('fetchId', $result['response']);
    }

    function updateItem($dType, $itemId, $itemDescription){
        /**
         * Update or create an item of the search engine
         *
         * In case of conflict between itemDescription's id or $itemId,
         * the latter is used.
         *
         * @param string $dType  type of the Item.
         * @param string $itemId  Id of the item to be updated/added
         * @param array $itemDescription Assoc array representating the item.
         * @return boolean true on success.
         */

        $result = $this->dma->managementApiCall(
            'PUT', $this->hashid."/items/$dType/$itemId", null, json_encode($itemDescription)
        );
        return $result['statusCode'] == 200;
    }

    function updateItems($dType, $itemsDescription){
        /**
         * Bulk update of several items
         *
         * Each item description must contain the 'id' field
         *
         * @param string $dType type of the items.
         * @param array $itemsDescription List of assoc array representing items
         * @return boolean true on success
         */
        $result = $this->dma->managementApiCall(
            'PUT', $this->hashid."/items/$dType", null, json_encode($itemsDescription)
        );
        return $result['statusCode'] == 200;
    }

    function deleteItem($dType, $itemId){
        /**
         * Delete an item
         *
         * @param string $dType type of the item
         * @param string $itemId id of the item
         * @return boolean true if success, false if failure
         */
        $result = $this->dma->managementApiCall(
            'DELETE', $this->hashid."/items/$dType/$itemId"
        );
        return $result['statusCode'] == 204 ;
    }

    function process(){
        /**
         * Ask the server to process the search engine's feeds
         *
         * @return array Assoc array with:
         *               - 'task_created': boolean true if a new task has been created
         *               - 'task_id': if task created, the id of the task.
         */
        $result = $this->dma->managementApiCall(
            'POST', $this->hashid."/tasks/process"
        );
        $taskCreated = ($result['statusCode'] == 201);
        $taskId = $taskCreated ? obtainId($result['response']['link']) : null;
        return array('task_created'=>$taskCreated, 'task_id'=>$taskId);
    }

    function processInfo(){
        /**
         * Obtain info of the last processing task sent to the server
         *
         * @return array Assoc array with 'state' and 'message' indicating status of the
         *               last asked processing task
         */
        $result = $this->dma->managementApiCall(
            'GET', $this->hashid."/tasks/process"
        );
        unset($result['response']['task_name']);
        return $result['response'];
    }

    function taskInfo($taskId){
        /**
         * Obtain info about how a task is going or its result
         *
         * @return array Assoc array with 'state' and 'message' indicating the status
         *               of the task
         */
        $result = $this->dma->managementApiCall(
            'GET', $this->hashid."/tasks/$taskId"
        );
        unset($result['response']['task_name']);
        return $result['response'];
    }


    function logs(){
        /**
         * Obtain logs of the latest feed processing tasks done
         *
         * @return array list of arrays representing the logs
         */
        $result = $this->dma->managementApiCall("GET", $this->hashid."/logs");
        return $result['response'];
    }


}

class ItemsRS implements Iterator {
    /**
     * Helper class to iterate through the search engine's items
     *
     * Implemets Iterator interface so foreach() can work with ItemRS
     */
    private $searchEngine = null;
    private $resultsPage = null;
    private $scrollId = null;
    private $position = 0;
    private $total = null;

    function __construct($searchEngine, $dType){
        $this->dType = $dType;
        $this->searchEngine = $searchEngine;
    }

    private function fetchResults(){
        $apiResults = $this->searchEngine->getScrolledItemsPage($this->dType, $this->scrollId);
        $this->total = $apiResults['total'];
        $this->resultsPage = $apiResults['results'];
        $this->scrollId = $apiResults['scroll_id'];
        $this->currentItem = each($this->resultsPage);
    }

    function rewind() {
        $this->scrollId = null;
        $this->fetchResults();
    }

    function valid(){
        return $this->position < $this->total;
    }

    function current(){
        return $this->currentItem['value'];
    }

    function key(){
        return $this->position;
    }

    function next(){
        ++$this->position;
        $this->currentItem = each($this->resultsPage);
        if(!$this->currentItem and $this->position < $this->total){
            $this->fetchResults();
            reset($this->resultsPage);
            $this->currentItem = each($this->resultsPage);
        }
    }
}

function obtainId($url){
    /**
     * Extracts identificator from an item or task url.
     *
     * @param string $url item or task resource locator
     * @return the item identificator
     */
    $urlRe = '~/\w{32}/(items/\w+|tasks)/([\w-_]+)/?$~';
    preg_match($urlRe, $url, $matches);
    return $matches[2];
}
