<?php
/**
 * Class with all the capabilities described in the API
 *
 * see https://redocly.github.io/redoc/?url=https://app.doofinder.com/api/v2/swagger.json#tag/SearchEngines
 */

namespace Doofinder\Management;

use DoofinderManagement\Api\SearchEnginesApi;
use DoofinderManagement\Api\ItemsApi;
use DoofinderManagement\Api\IndicesApi;

use DoofinderManagement\Configuration;
use GuzzleHttp\Client;


class ManagementClient {
    protected $config = null;
    protected $client = null;

    protected $searchEnginesClient = null;
    protected $ItemsClient = null;
    protected $IndicesClient = null;
  
    /**
     * Create a new ManagementClient instance
     * 
     * @param Array $credentials. (required)
     * @return ManagementClient instance created.
     */
    public function __construct() {
      $this->config = Configuration::getDefaultConfiguration();
      $this->client = new Client();
      
      $this->searchEngineClient = new SearchEnginesApi(
        $this->client,
        $this->config
      );
      
      $this->ItemsClient = new ItemsApi(
        $this->client,
        $this->config
      );
      
      $this->IndicesClient = new IndicesApi(
        $this->client,
        $this->config
      );
    }

    public function getConfig() {
        return $this->config;
    }

    public function setApiKey($value) {
        $this->config->setApiKey('Authorization', $value);
        $this->config->setApiKeyPrefix('Authorization', 'Token');
    }

    public function setBearerToken($value) {
        $this->config->setApiKey('Authorization', $value);
        $this->config->setApiKeyPrefix('Authorization', 'Bearer');
    }

    public function setHost($host) {
        $this->config->setHost($host);
    }

    /**
     * Process all search engine's data sources.
     * 
     * @param string $hashid Unique id of a search engine. (required)
     * 
     * @return \DoofinderManagement\Model\ProcessingTask
     */
    public function processSearchEngine($hashid) {
        return $this->searchEngineClient->process($hashid);
    }
  
    /**
     * Gets the status of the process task.
     * 
     * @param string $hashid Unique id of a search engine. (required)
     * 
     * @return \DoofinderManagement\Model\ProcessingTask
     */
    public function getProcessStatus($hashid) {
        return $this->searchEngineClient->processStatus($hashid);
    }

    /**
     * Create SearchEngine
     * 
     * @param object $body attributes to create a Search Engine. (required)
     * 
     * @return \DoofinderManagement\Model\SearchEngine
     */
    public function createSearchEngine($body) {
        return $this->searchEngineClient->searchEngineCreate($body);
    }
    
    /**
     * Get SearchEngine
     *
     * @param string $hashid Unique id of a search engine. (required)
     * 
     * @return \DoofinderManagement\Model\SearchEngine
     */
    public function getSearchEngine($hashid) {
        return $this->searchEngineClient->searchEngineShow($hashid);
    }
    
    /**
     * Delete SearchEngine
     *
     * @param string $hashid Unique id of a search engine. (required)
     */
    public function deleteSearchEngine($hashid) {
    $this->searchEngineClient->searchEngineDelete($hashid);
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
        return $this->searchEngineClient->searchEngineUpdate($body, $hashid);
    }

    /**
     * List SearchEngines
     *
     * @return \DoofinderManagement\Model\SearchEngines
     */
    public function listSearchEngines(){
        return $this->searchEngineClient->searchEngineList();
    }

    /**
     * Return the status of the current reindexing task.
     * 
     * @param string $hashid Unique id of a search engine. (required)
     * @param string $name Name of an index. (required)
     * @return \DoofinderManagement\Model\ReindexingTask
     */
    public function returnReindexingStatus($hashid, $name) {
        return $this->IndicesClient->getReindexingStatus($hashid, $name);
    }
    
    /**
     * Creates an Index.
     * 
     * @param string $hashid Unique id of a search engine. (required)
     * @param object $body attributes to create an Index. (required)
     * @return \DoofinderManagement\Model\Index
     */
    public function createIndex($hashid, $body) {
        return $this->IndicesClient->indexCreate($body, $hashid);
    }
    
    /**
     * Deletes an Index.
     *
     * @param string $hashid Unique id of a search engine. (required)
     * @param string $name Name of an index. (required)
     * @return void
     */
    public function deleteIndex($hashid, $name) {
      $this->IndicesClient->indexDelete($hashid, $name);
    }

    /**
     * List Indices
     * 
     * @param  string $hashid Unique id of a search engine. (required)
     * @return \DoofinderManagement\Model\Indices
     */
    public function listIndices($hashid){
        return $this->IndicesClient->indexIndex($hashid);
    }
    
    /**
     * Gets an Index
     *
     * @param string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * @return \DoofinderManagement\Model\Index
     */
    public function getIndex($hashid, $name) {
        return $this->IndicesClient->indexShow($hashid, $name);
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
        return $this->IndicesClient->indexUpdate($body, $hashid, $name);
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
        return $this->IndicesClient->reindexToTemp($hashid, $name);
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
        return $this->IndicesClient->replaceByTemp($hashid, $name);
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
        return $this->IndicesClient->temporaryIndexCreate($hashid, $name);
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
        return $this->IndicesClient->temporaryIndexDelete($hashid, $name);
    }

    /**
     * Creates an item.
     * 
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * @param  map[string,object] $body body (required)
     * 
     * @return \DoofinderManagement\Model\Item
     */
    public function createItem($hashid, $name, $body) {
        return $this->ItemsClient->itemCreate($body, $hashid, $name);
    }
  
    /**
     * Deletes an item from the index.
     * 
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $item_id Unique identifier of an item inside an index. (required)
     * @param  string $name Name of an index. (required)
     * 
     * @return void
     */
    public function deleteItem($hashid, $item_id, $name) {
        return $this->ItemsClient->itemDelete($hashid, $name, $item_id);
    }

    /**
     * Scrolls through all index items
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * @param  string $scroll_id Unique identifier for the scroll. The scroll saves a "pointer" to the last fetched page so each successive request to the same scroll_id return a new page. (optional)
     * @param  int $rpp _Results per page_. How many items are fetched per page/request. (optional)
 
    * 
    * @return \DoofinderManagement\Model\Scroller
    */
    public function scrollsItems($hashid, $name, $scroll_id = null, $rpp = null) {
        return $this->ItemsClient->itemIndex($hashid, $name, $scroll_id, $rpp);
    }

    /**
     * Gets an item from the index.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $item_id Unique identifier of an item inside an index. (required)
     * @param  string $name Name of an index. (required)
     *
     * @return \DoofinderManagement\Model\Item
     */
    public function getItem($hashid, $item_id, $name) {
        return $this->ItemsClient->itemShow($hashid, $name, $item_id);
    }

    /**
     * Creates an item in the temporal index.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * @param  map[string,object] $body body (required)
     *
     * @return \DoofinderManagement\Model\Item
     */
    public function createTempItem($hashid, $name, $body) {
        return $this->ItemsClient->itemTempCreate($hashid, $name, $body);
    }

    /**
     * Deletes an item in the temporal index.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $item_id Unique identifier of an item inside an index. (required)
     * @param  string $name Name of an index. (required)
     *
     * @return void
     */
    public function deleteTempItem($hashid, $item_id, $name) {
        return $this->ItemsClient->itemTempDelete($hashid, $name, $item_id);
    }

    /**
     * Gets an item from the temporal index.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $item_id Unique identifier of an item inside an index. (required)
     * @param  string $name Name of an index. (required)
     *
     * @return \DoofinderManagement\Model\Item
     */
    public function getTempItem($hashid, $item_id, $name) {
        return $this->ItemsClient->itemTempShow($hashid, $name, $item_id);
    }

    /**
     * Partially updates an item in the temporal index.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $item_id Unique identifier of an item inside an index. (required)
     * @param  string $name Name of an index. (required)
     * @param  map[string,object] $body body (required)
     *
     * @return \DoofinderManagement\Model\Item
     */
    public function updateTempItem($hashid, $item_id, $name, $body) {
        return $this->ItemsClient->itemTempUpdate($body, $hashid, $name, $item_id);
    }

    /**
     * Partially updates an item in the index.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $item_id Unique identifier of an item inside an index. (required)
     * @param  string $name Name of an index. (required)
     * @param  map[string,object] $body body (required)
     *
     * @return \DoofinderManagement\Model\Item
     */
    public function updateItem($hashid, $item_id, $name, $body) {
        return $this->ItemsClient->itemUpdate($body, $hashid, $name, $item_id);
    }

    /**
     * Creates a bulk of item in the index.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * @param  \DoofinderManagement\Model\ItemsIdsInner[] $body body (required)
     *
     * @return \DoofinderManagement\Model\BulkResult
     */
    public function createBulk($hashid, $name, $body) {
        return $this->ItemsClient->itemsBulkCreate($body, $hashid, $name);
    }

    /**
     * Deletes a bulk of items from the index.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * @param  \DoofinderManagement\Model\Item[] $body body (required)
     *
     * @return \DoofinderManagement\Model\BulkResult
     */
    public function deleteBulk($hashid, $name, $body) {
        return $this->ItemsClient->itemsBulkDelete($body, $hashid, $name);
    }

    /**
     * Partial updates a bulk of items in the index.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * @param  \DoofinderManagement\Model\Item[] $body body (required)
     *
     * @return \DoofinderManagement\Model\BulkResult
     */
    public function updateBulk($hashid, $name, $body) {
        return $this->ItemsClient->itemsBulkUpdate($body, $hashid, $name);
    }

    /**
     * Creates a bulk of items in the temporal index.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * @param  \DoofinderManagement\Model\Item[] $body body (required)
     *
     * @return \DoofinderManagement\Model\BulkResult
     */
    public function createTempBulk($hashid, $name, $body) {
        return $this->ItemsClient->itemsTempBulkCreate($body, $hashid, $name);
    }

    /**
     * Deletes items in bulk in the temporal index.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * @param  \DoofinderManagement\Model\ItemsIdsInner[] $body body (required)
     *
     * @return \DoofinderManagement\Model\BulkResult
     */
    public function deleteTempBulk($hashid, $name, $body) {
        return $this->ItemsClient->itemsTempBulkDelete($body, $hashid, $name);
    }

    /**
     * Partial updates a bulk of items in the temporal index.
     *
     * @param  string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * @param  \DoofinderManagement\Model\Item[] $body body (required)
     *
     * @return \DoofinderManagement\Model\BulkResult
     */
    public function updateTempBulk($hashid, $name, $body) {
        return $this->ItemsClient->itemsTempBulkUpdate($body, $hashid, $name);
    }
  }
  