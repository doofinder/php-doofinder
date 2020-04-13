<?php
/**
 * Class with all the capabilities described in the API
 *
 * see http://www.doofinder.com/developer/topics/api/management-api
 */

namespace DoofinderManagement;

use DoofinderManagement\Api\SearchEnginesApi;
use GuzzleHttp\Client;


class SearchEngineClient {
    public $config = null;
    public $api = null;
  
  
    public function __construct($config) {
      $this->config = $config;
      $this->api = new Api\SearchEnginesApi(
        new Client(),
        $config
      );
    }
    /**
     * Add SearchEngine
     * 
     * @param object $body attributes to create a Search Engine (required)
     * @return serchEngine object created
     */
    public function addSearchEngine($body) {
      return $this->api->searchEngineCreate($body);
    }
    
    /**
     * Delete SearchEngine
     *
     * @param string $hashid Unique id of a search engine. (required)
     */
    public function deleteSearchEngine($hashid) {
      $this->api->searchEngineDelete($hashid);
    }
  
    /**
     * Update SearchEngine
     *
     * @param  object $body (required)
     * @param  string $hashid Unique id of a search engine. (required)
     * @return searchEngine object, updated
     */
    public function updateSearchEngine($body, $hashid){
      return $this->api->searchEngineUpdate($body, $hashid);
    }
  
  }
  