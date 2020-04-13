<?php
/**
 * Class with all the capabilities described in the API
 *
 * see #
 */

namespace Doofinder\Management;

use DoofinderManagement\Api\SearchEnginesApi;
use GuzzleHttp\Client;


class SearchEngineClient {
    public $config = null;
    public $api = null;
  
    /**
     * Create a new SearchEngineClient instance
     * 
     * @param DoofinderManagement\Configuration $config instance previously created (required)
     * @return serchEngineCliente instance created
     */
    public function __construct($config) {
      $this->config = $config;
      $this->api = new SearchEnginesApi(
        new Client(),
        $config
      );
    }
    /**
     * Create SearchEngine
     * 
     * @param object $body attributes to create a Search Engine (required)
     * @return serchEngine object created
     */
    public function createSearchEngine($body) {
      return $this->api->searchEngineCreate($body);
    }
    
    /**
     * Get SearchEngine
     *
     * @param string $hashid Unique id of a search engine. (required)
     * @return serchEngine object
     */
    public function getSearchEngine($hashid) {
      return $this->api->searchEngineShow($hashid);
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
    public function updateSearchEngine($hashid, $body){
      return $this->api->searchEngineUpdate($body, $hashid);
    }

    /**
     * List SearchEngines
     *
     * @return searchEngine array
     */
    public function listSearchEngines(){
        return $this->api->searchEngineList();
      }
  
  }
  