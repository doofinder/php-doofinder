<?php
/**
 * Class with all the capabilities described in the API
 *
 * see https://redocly.github.io/redoc/?url=https://app.doofinder.com/api/v2/swagger.json#tag/SearchEngines
 */

namespace Doofinder\Management;

use Doofinder\Management\Errors\Utils;
use DoofinderManagement\Api\SearchEnginesApi;
use DoofinderManagement\Api\ItemsApi;
use DoofinderManagement\Api\IndicesApi;
use DoofinderManagement\Configuration;
use DoofinderManagement\ApiException;
use GuzzleHttp\Client;


class ManagementClient {
    protected $config = null;
    protected $client = null;
    protected $host = null;
    protected $token = null;

    protected $searchEnginesClient = null;
    protected $ItemsClient = null;
    protected $IndicesClient = null;
  
    /**
     * Create a new ManagementClient instance
     * 
     * @param Array $credentials. (required)
     * @return ManagementClient instance created.
     */
    public function __construct($host, $token) {
      $this->config = Configuration::getDefaultConfiguration();
      $this->client = new Client();
      $this->setHost($host);
      $this->setApiKey($token);

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
        try {
            return $this->searchEngineClient->process($hashid);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
    }
  
    /**
     * Gets the status of the process task.
     * 
     * @param string $hashid Unique id of a search engine. (required)
     * 
     * @return \DoofinderManagement\Model\ProcessingTask
     */
    public function getProcessStatus($hashid) {
        try {
            return $this->searchEngineClient->processStatus($hashid);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
    }

    /**
     * Create SearchEngine
     * 
     * @param object $body attributes to create a Search Engine. (required)
     * 
     * @return \DoofinderManagement\Model\SearchEngine
     */
    public function createSearchEngine($body) {
        try {
            return $this->searchEngineClient->searchEngineCreate($body);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
    }
    
    /**
     * Get SearchEngine
     *
     * @param string $hashid Unique id of a search engine. (required)
     * 
     * @return \DoofinderManagement\Model\SearchEngine
     */
    public function getSearchEngine($hashid) {
        try {
            return $this->searchEngineClient->searchEngineShow($hashid);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
    }
    
    /**
     * Delete SearchEngine
     *
     * @param string $hashid Unique id of a search engine. (required)
     */
    public function deleteSearchEngine($hashid) {
        try {
            $this->searchEngineClient->searchEngineDelete($hashid);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->searchEngineClient->searchEngineUpdate($body, $hashid);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
    }

    /**
     * List SearchEngines
     *
     * @return \DoofinderManagement\Model\SearchEngines
     */
    public function listSearchEngines(){
        try {
            return $this->searchEngineClient->searchEngineList();
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
    }

    /**
     * Return the status of the current reindexing task.
     * 
     * @param string $hashid Unique id of a search engine. (required)
     * @param string $name Name of an index. (required)
     * @return \DoofinderManagement\Model\ReindexingTask
     */
    public function returnReindexingStatus($hashid, $name) {
        try {
            return $this->IndicesClient->getReindexingStatus($hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
    }
    
    /**
     * Creates an Index.
     * 
     * @param string $hashid Unique id of a search engine. (required)
     * @param object $body attributes to create an Index. (required)
     * @return \DoofinderManagement\Model\Index
     */
    public function createIndex($hashid, $body) {
        try {
            return $this->IndicesClient->indexCreate($body, $hashid);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
    }
    
    /**
     * Deletes an Index.
     *
     * @param string $hashid Unique id of a search engine. (required)
     * @param string $name Name of an index. (required)
     * @return void
     */
    public function deleteIndex($hashid, $name) {
        try {
            $this->IndicesClient->indexDelete($hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
    }

    /**
     * List Indices
     * 
     * @param  string $hashid Unique id of a search engine. (required)
     * @return \DoofinderManagement\Model\Indices
     */
    public function listIndices($hashid){
        try {
            return $this->IndicesClient->indexIndex($hashid);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
    }
    
    /**
     * Gets an Index
     *
     * @param string $hashid Unique id of a search engine. (required)
     * @param  string $name Name of an index. (required)
     * @return \DoofinderManagement\Model\Index
     */
    public function getIndex($hashid, $name) {
        try {
            return $this->IndicesClient->indexShow($hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->IndicesClient->indexUpdate($body, $hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->IndicesClient->reindexToTemp($hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->IndicesClient->replaceByTemp($hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->IndicesClient->temporaryIndexCreate($hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->IndicesClient->temporaryIndexDelete($hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemCreate($body, $hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemDelete($hashid, $name, $item_id);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemIndex($hashid, $name, $scroll_id, $rpp);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemShow($hashid, $name, $item_id);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemTempCreate($hashid, $name, $body);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemTempDelete($hashid, $name, $item_id);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemTempShow($hashid, $name, $item_id);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemTempUpdate($body, $hashid, $name, $item_id);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemUpdate($body, $hashid, $name, $item_id);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemsBulkCreate($body, $hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemsBulkDelete($body, $hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemsBulkUpdate($body, $hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemsTempBulkCreate($body, $hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemsTempBulkDelete($body, $hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
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
        try {
            return $this->ItemsClient->itemsTempBulkUpdate($body, $hashid, $name);
        } catch (ApiException $e) {
            $statusCode = $e->getCode();
            $contentResponse = $e->getResponseBody();
            $error = Utils::handleErrors($statusCode, $contentResponse);
            
            throw $error;
        }
    }
  }
  