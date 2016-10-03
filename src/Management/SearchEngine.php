<?php
/**
 * Class with all the capabilities described in the API
 *
 * see http://www.doofinder.com/developer/topics/api/management-api
 */

namespace Doofinder\Api\Management;

use Doofinder\Api\Management\Errors\BadRequest;
use Doofinder\Api\Management\TopTermsIterator;
use Doofinder\Api\Management\ScrollIterator;
use Doofinder\Api\Management\AggregatesIterator;


class SearchEngine {
  public $name = null;
  public $hashid = null;

  public $dma = null; // DoofinderManagementApi instance

  function __construct($dma, $hashid, $name){
    $this->name = $name;
    $this->hashid = $hashid;
    $this->dma = $dma;
  }

  /**
   * Get a list of searchengine's types
   *
   * @return array list of types
   */
  function getDatatypes(){
    return $this->getTypes();
  }

  /**
   * Get a list of searchengine's types
   *
   * @return array list of types
   */
  function getTypes(){
    $result = $this->dma->managementApiCall('GET', "{$this->hashid}/types");
    return $result['response'];
  }

  /**
   * Add a type to the searchengine
   *
   * @param string $dType the type name
   * @return new list of searchengine's types
   */
  function addType($dType){
    $result = $this->dma->managementApiCall('POST', "{$this->hashid}/types", null,
                                            json_encode(array('name' => $dType)));
    return $result['response'];
  }

  /**
   * Delete a type and all its items. HANDLE WITH CARE
   *
   * @param string $dType the Type to delete. All items belonging
   *                          to that type will be removed. mandatory
   * @return boolean true on success
   */
  function deleteType($dType){
    $result = $this->dma->managementApiCall('DELETE', "{$this->hashid}/types/{$dType}");
    return $result['statusCode'] == 204;
  }

  function items($dType){
    return new ScrollIterator($this, $dType);
  }

  /**
   * Get details of a specific item
   *
   * @param string $dType Type of the item.
   * @param string $itemId the id of the item
   * @return array Assoc array representing the item.
   */
  function getItem($dType, $itemId){
    $result = $this->dma->managementApiCall('GET', "{$this->hashid}/items/{$dType}/{$itemId}");
    return $result['response'];
  }

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
  function addItem($dType, $itemDescription){
    $result = $this->dma->managementApiCall('POST', "{$this->hashid}/items/{$dType}", null,
                                            json_encode($itemDescription));
    return $result['response']['id'];
  }

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
  function addItems($dType, $itemsDescription){
    $result = $this->dma->managementApiCall('POST', "{$this->hashid}/items/{$dType}", null,
                                            json_encode($itemsDescription));

    function fetchId($item){
      return $item['id'];
    };

    return array_map('fetchId', $result['response']);
  }

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
  function updateItem($dType, $itemId, $itemDescription){
    $result = $this->dma->managementApiCall('PUT', "{$this->hashid}/items/{$dType}/{$itemId}", null,
                                            json_encode($itemDescription));
    return $result['statusCode'] == 200;
  }

  /**
   * Bulk update of several items
   *
   * Each item description must contain the 'id' field
   *
   * @param string $dType type of the items.
   * @param array $itemsDescription List of assoc array representing items
   * @return boolean true on success
   */
  function updateItems($dType, $itemsDescription){
    $result = $this->dma->managementApiCall('PUT', "{$this->hashid}/items/{$dType}", null,
                                            json_encode($itemsDescription));
    return $result['statusCode'] == 200;
  }

  /**
   * Delete an item
   *
   * @param string $dType type of the item
   * @param string $itemId id of the item
   * @return boolean true if success, false if failure
   */
  function deleteItem($dType, $itemId){
    $result = $this->dma->managementApiCall('DELETE', "{$this->hashid}/items/{$dType}/{$itemId}");
    return $result['statusCode'] == 204 ;
  }

  /**
   * Obtain stats aggregated data for a certain period.
   *
   * @param DateTime $from_date. Starting date. Default is 15 days ago
   * @param DateTime $to_date. Ending date. Default is today
   *
   * @return ItemsRS iterator through daily aggregated data.
   */
  function stats($from_date=null, $to_date=null){
    return new AggregatesIterator($this, $from_date, $to_date);
  }

  /**
   * Obtain frequency sorted list of therms used for a certain period.
   *
   * @param string term: type of term 'clicked', 'searches', 'opportunities'
   *                     - 'clicked': clicked items
   *                     - 'searches': complete searches
   *                     - 'opportunities': searches without results
   * @param DateTime $from_date. Starting date. Default is 15 days ago
   * @param DateTime $to_date. Ending date. Default is today
   *
   * @return ItemsRS iterator through terms stats.
   */
  function topTerms($term, $from_date=null, $to_date=null){

    if(!in_array($term, array('clicked', 'searches', 'opportunities'))){
      throw new BadRequest("The term {$term} is not allowed");
    }
    return new TopTermsIterator($this, $term, $from_date, $to_date);
  }

  /**
   * Ask the server to process the search engine's feeds
   *
   * @return array Assoc array with:
   *               - 'task_created': boolean true if a new task has been created
   *               - 'task_id': if task created, the id of the task.
   */
  function process(){
    $result = $this->dma->managementApiCall('POST', "{$this->hashid}/tasks/process");
    $taskCreated = ($result['statusCode'] == 201);
    $taskId = $taskCreated ? SearchEngine::obtainId($result['response']['link']) : null;
    return array('task_created'=>$taskCreated, 'task_id' => $taskId);
  }

  /**
   * Obtain info of the last processing task sent to the server
   *
   * @return array Assoc array with 'state' and 'message' indicating status of the
   *               last asked processing task
   */
  function processInfo(){
    $result = $this->dma->managementApiCall('GET', "{$this->hashid}/tasks/process");
    unset($result['response']['task_name']);
    return $result['response'];
  }

  /**
   * Obtain info about how a task is going or its result
   *
   * @return array Assoc array with 'state' and 'message' indicating the status
   *               of the task
   */
  function taskInfo($taskId){
    $result = $this->dma->managementApiCall('GET', "{$this->hashid}/tasks/{$taskId}");
    unset($result['response']['task_name']);
    return $result['response'];
  }

  /**
   * Obtain logs of the latest feed processing tasks done
   *
   * @return array list of arrays representing the logs
   */
  function logs(){
    $result = $this->dma->managementApiCall("GET", "{$this->hashid}/logs");
    return $result['response'];
  }

  /**
   * Extracts identificator from an item or task url.
   *
   * @param string $url item or task resource locator
   * @return the item identificator
   */
    private static function obtainId($url){
        preg_match('~/\w{32}/(items/\w+|tasks)/([\w-_]+)/?$~', $url, $matches);
        return $matches[2];
    }
}
