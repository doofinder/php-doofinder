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
        $this->baseManagementUrl = $this->clusterRegion."-".self::MANAGEMENT_DOMAIN_SUFFIX.
            "/v".self::MANAGEMENT_VERSION;
//        $this->baseManagementUrl = 'localhost:8000/api/v1';
    }

    function managementApiCall($method='GET', $entryPoint='', $params=null, $data=null){
        $headers = array('Authorization: Token '.$this->token,
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

        return array('statusCode' => $httpCode, 'response' => json_decode($response, true));

    }

    function getApiRoot() {
        return $this->managementApiCall()['response'];
    }

    function getSearchEngines() {
        $searchEngines = array();
        $apiRoot = $this->getApiRoot();
        unset($apiRoot['searchengines']);
        foreach($apiRoot as $hashid => $props){
            $searchEngines[] = new SearchEngine($this, $hashid, $props['name']);
        }
        return $searchEngines;
    }

    function show() {
//        echo $this->baseManagementUrl;
        echo "\n";
//        echo $this->token;
        echo "\n";
//        echo $this->baseManagementUrl;
        $a = 'a';

    }


}

class SearchEngine {

    public $name = null;
    public $hashid = null;
    private $dma = null; //DoofinderManagementApi instance

    function __construct($dma, $hashid, $name) {
        $this->name = $name;
        $this->hashid = $hashid;
        $this->dma = $dma;
    }

    function getDatatypes(){
        return $this->getTypes();
    }

    function getTypes() {
        $result = $this->dma->managementApiCall('GET', $this->hashid.'/types');
        return $result['response'];
    }

    function addType($dtype) {
        $result = $this->dma->managementApiCall(
            'POST', $this->hashid.'/types', null, json_encode(array('name'=>$dtype))
        );
        return $result['response'];
    }

    function deleteType($dtype) {
        $result = $this->dma->managementApiCall(
            'DELETE', $this->hashid.'/types/'.$dtype
        );
        if($result['statusCode'] == 204){
            return true;
        } else {
            return false;
        }
    }

    function items($dtype, $scrollId = null) {
        $params = $scrollId ? array("scroll_id"=>$scrollId) : null;
        $result = $this->dma->managementApiCall(
            'GET', $this->hashid.'/items/'.$dtype,
            $params
        );
        return array(
            'scroll_id' => $result['response']['scroll_id'],
            'results' => $result['response']['results']
        );
    }

    function get_item($dtype, $item_id) {
        $result = $this->dma->managementApiCall(
            'GET', $this->hashid."/items/$dtype/$item_id");
        return $result['response'];
    }

    function add_item($dtype, $item_description){
        $result = $this->dma->managementApiCall(
            'POST', $this->hashid."/items/$dtype", null, json_encode($item_description)
        );
        return $result['response']['id'];
    }

    function add_items($dtype, $items_description){

    }
}

function obtainId($url){
    $urlRe = '~/\w{32}/(items/\w+|tasks)/([\w-_]+)/?$~';
    preg_match($urlRe, $url, $matches);
    return $matches[2];
}
