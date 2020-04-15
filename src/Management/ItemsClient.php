<?php
/**
 * Class with all the capabilities described in the API
 *
 * see https://redocly.github.io/redoc/?url=https://app.doofinder.com/api/v2/swagger.json#tag/Items
 */

namespace Doofinder\Management;

use DoofinderManagement\Api\ItemsApi;
use GuzzleHttp\Client;


class ItemsClient {
    public $config = null;
    public $api = null;
  
    /**
     * Create a new ItemsClient instance
     * 
     * @param DoofinderManagement\Configuration $config instance previously created. (required)
     * @return ItemsClient instance created.
     */
    public function __construct($config) {
      $this->config = $config;
      $this->api = new ItemsApi(
        new Client(),
        $config
      );
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
      return $this->api->itemCreate($body, $hashid, $name);
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
      return $this->api->itemDelete($hashid, $name, $item_id);
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
      return $this->api->itemIndex($hashid, $name, $scroll_id, $rpp);
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
      return $this->api->itemShow($hashid, $name, $item_id);
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
      return $this->api->itemTempCreate($hashid, $name, $body);
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
      return $this->api->itemTempDelete($hashid, $name, $item_id);
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
      return $this->api->itemTempShow($hashid, $name, $item_id);
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
      return $this->api->itemTempUpdate($body, $hashid, $name, $item_id);
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
      return $this->api->itemUpdate($body, $hashid, $name, $item_id);
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
      return $this->api->itemsBulkCreate($body, $hashid, $name);
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
      return $this->api->itemsBulkDelete($body, $hashid, $name);
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
      return $this->api->itemsBulkUpdate($body, $hashid, $name);
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
      return $this->api->itemsTempBulkCreate($body, $hashid, $name);
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
      return $this->api->itemsTempBulkDelete($body, $hashid, $name);
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
      return $this->api->itemsTempBulkUpdate($body, $hashid, $name);
    }
    
  
  }
  