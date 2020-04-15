<?php
/**
 * Class with all the capabilities described in the API
 *
 * see https://redocly.github.io/redoc/?url=https://app.doofinder.com/api/v2/swagger.json#tag/SearchEngines
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
     * @param DoofinderManagement\Configuration $config instance previously created. (required)
     * @return serchEngineClient instance created.
     */
    public function __construct($config) {
      $this->config = $config;
      $this->api = new SearchEnginesApi(
        new Client(),
        $config
      );
    }

    /**
     * Process all search engine's data sources.
     * 
     * @param string $hashid Unique id of a search engine. (required)
     * 
     * @return \DoofinderManagement\Model\ProcessingTask
     */
    public function processSearchEngine($hashid) {
      return $this->api->process($hashid);
    }

    /**
     * Gets the status of the process task.
     * 
     * @param string $hashid Unique id of a search engine. (required)
     * 
     * @return \DoofinderManagement\Model\ProcessingTask
     */
    public function getProcessStatus($hashid) {
      return $this->api->processStatus($hashid);
    }

    /**
     * Create SearchEngine
     * 
     * @param object $body attributes to create a Search Engine. (required)
     * 
     * @return \DoofinderManagement\Model\SearchEngine
     */
    public function createSearchEngine($body) {
      return $this->api->searchEngineCreate($body);
    }
    
    /**
     * Get SearchEngine
     *
     * @param string $hashid Unique id of a search engine. (required)
     * 
     * @return \DoofinderManagement\Model\SearchEngine
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
     * 
     * @return \DoofinderManagement\Model\SearchEngine
     */
    public function updateSearchEngine($hashid, $body){
      return $this->api->searchEngineUpdate($body, $hashid);
    }

    /**
     * List SearchEngines
     *
     * @return \DoofinderManagement\Model\SearchEngines
     */
    public function listSearchEngines(){
        return $this->api->searchEngineList();
      }
  
  }
  