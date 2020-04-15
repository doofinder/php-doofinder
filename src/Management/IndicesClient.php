<?php
/**
 * Class with all the capabilities described in the API
 *
 * see https://redocly.github.io/redoc/?url=https://app.doofinder.com/api/v2/swagger.json#tag/Indices
 */

namespace Doofinder\Management;

use DoofinderManagement\Api\IndicesApi;
use GuzzleHttp\Client;


class IndicesClient {
    public $config = null;
    public $api = null;
  
    /**
     * Create a new IndicesClient instance
     * 
     * @param DoofinderManagement\Configuration $config instance previously created (required)
     * @return IndicesClient instance created
     */
    public function __construct($config) {
      $this->config = $config;
      $this->api = new IndicesApi(
        new Client(),
        $config
      );
    }

    /**
     * Return the status of the current reindexing task.
     * 
     * @param string $hashid Unique id of a search engine. (required)
     * @param string $name Name of an index. (required)
     * @return \DoofinderManagement\Model\ReindexingTask
     */
    public function returnReindexingStatus($hashid, $name) {
        return $this->api->getReindexingStatus($hashid, $name);
    }
    
    /**
     * Creates an Index.
     * 
     * @param string $hashid Unique id of a search engine. (required)
     * @param object $body attributes to create an Index. (required)
     * @return \DoofinderManagement\Model\Index
     */
    public function createIndex($hashid, $body) {
        return $this->api->indexCreate($body, $hashid);
    }
    
    /**
     * Deletes an Index.
     *
     * @param string $hashid Unique id of a search engine. (required)
     * @param string $name Name of an index. (required)
     * @return void
     */
    public function deleteIndex($hashid, $name) {
      $this->api->indexDelete($hashid, $name);
    }

    /**
     * List Indices
     * 
     * @param  string $hashid Unique id of a search engine. (required)
     * @return \DoofinderManagement\Model\Indices
     */
    public function listIndices($hashid){
        return $this->api->indexIndex($hashid);
    }
    
    /**
     * Gets an Index
     *
     * @param string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * @return \DoofinderManagement\Model\Index
     */
    public function getIndex($hashid, $name) {
      return $this->api->indexShow($hashid, $name);
    }
  
    /**
     * Updates Index
     *
     * @param  object $body body (required)
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * 
     * @return \DoofinderManagement\Model\Index
     */
    public function updateIndex($hashid, $name, $body){
      return $this->api->indexUpdate($body, $hashid, $name);
    }
  
    /**
     * Reindex the content of the real index into the temporary one.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * 
     * @return object
     */
    public function reindex($hashid, $name){
      return $this->api->reindexToTemp($hashid, $name);
    }
  
    /**
     * Replace the real index with the temporary one.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * 
     * @return object
     */
    public function replace($hashid, $name){
      return $this->api->replaceByTemp($hashid, $name);
    }
  
    /**
     * Creates a temporary index.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * 
     * @return object
     */
    public function createTemporaryIndex($hashid, $name){
      return $this->api->temporaryIndexCreate($hashid, $name);
    }
  
    /**
     * Deletes a temporary index.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * 
     * @return void
     */
    public function deleteTemporaryIndex($hashid, $name){
      return $this->api->temporaryIndexDelete($hashid, $name);
    }
  
  }
  