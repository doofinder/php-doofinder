<?php
/**
 * Class with all the capabilities described in the API
 *
 * see http://www.doofinder.com/developer/topics/api/management-api
 */

namespace Doofinder\Api\Management;

use Doofinder\Api\Management\Client;
use Doofinder\Api\Management\Errors\BadRequest;
use Doofinder\Api\Management\TopTermsIterator;
use Doofinder\Api\Management\ScrollIterator;
use Doofinder\Api\Management\AggregatesIterator;


class SearchEngine {
  public $name = null;
  public $hashid = null;
  public $client = null;

  public function __construct(Client $client, $hashid, $name) {
    $this->name = $name;
    $this->hashid = $hashid;
    $this->client = $client;
  }

  /**
   * Get a list of searchengine's types
   *
   * @return array list of types
   */
  public function getDatatypes() {
    return $this->getTypes();
  }

  /**
   * Get a list of searchengine's types
   *
   * @return array list of types
   */
  public function getTypes() {
    $result = $this->client->managementApiCall('GET', "{$this->hashid}/types");
    return $result['response'];
  }

  /**
   * Add a type to the searchengine
   *
   * @param string $datatype the type name
   * @return new list of searchengine's types
   */
  public function addType($datatype) {
    $result = $this->client->managementApiCall('POST', "{$this->hashid}/types", null, json_encode(array('name' => $datatype)));
    return $result['response'];
  }

  /**
   * Delete a type and all its items. HANDLE WITH CARE
   *
   * @param string $datatype the Type to delete. All items belonging
   *                          to that type will be removed. mandatory
   * @return boolean true on success
   */
  public function deleteType($datatype) {
    $result = $this->client->managementApiCall('DELETE', "{$this->hashid}/types/{$datatype}");
    return $result['statusCode'] == 204;
  }

  public function items($datatype) {
    return new ScrollIterator($this, $datatype);
  }

  /**
   * Get details of a specific item
   *
   * @param string $datatype Type of the item.
   * @param string $itemId the id of the item
   * @return array Assoc array representing the item.
   */
  public function getItem($datatype, $itemId) {
    $result = $this->client->managementApiCall('GET', "{$this->hashid}/items/{$datatype}/{$itemId}");
    return $result['response'];
  }

  /**
   * Add an item to the search engine
   *
   *   - If the 'id' field is present, use that as item's id or overwrite an existing
   *     item with that id.
   *   - It the 'id' field is not present, create one.
   *
   * @param string $datatype type of the. If not provided, first available type is used
   * @param array $itemDescription Assoc array representation of the item
   * @return string the id of the item just created
   */
  public function addItem($datatype, $itemDescription) {
    $result = $this->client->managementApiCall('POST', "{$this->hashid}/items/{$datatype}", null, json_encode($itemDescription));
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
   * @param string $datatype type of the. If not provided, first available type is used
   * @param array $itemsDescription List of Assoc array representation of the item
   * @return array List of ids of the added items
   */
  public function addItems($datatype, $itemsDescription) {
    $result = $this->client->managementApiCall('POST', "{$this->hashid}/items/{$datatype}", null, json_encode($itemsDescription));

    return array_map(function($it){return $it['id'];}, $result['response']);
  }

  /**
   * Update or create an item of the search engine
   *
   * In case of conflict between itemDescription's id or $itemId,
   * the latter is used.
   *
   * @param string $datatype  type of the Item.
   * @param string $itemId  Id of the item to be updated/added
   * @param array $itemDescription Assoc array representating the item.
   * @return boolean true on success.
   */
  public function updateItem($datatype, $itemId, $itemDescription) {
    $result = $this->client->managementApiCall('PUT', "{$this->hashid}/items/{$datatype}/{$itemId}", null, json_encode($itemDescription));
    return $result['statusCode'] == 200;
  }

  /**
   * Bulk update of several items
   *
   * Each item description must contain the 'id' field
   *
   * @param string $datatype type of the items.
   * @param array $itemsDescription List of assoc array representing items
   * @return boolean true on success
   */
  public function updateItems($datatype, $itemsDescription) {
    $result = $this->client->managementApiCall('PUT', "{$this->hashid}/items/{$datatype}", null, json_encode($itemsDescription));
    return $result['statusCode'] == 200;
  }

  /**
   * Delete an item
   *
   * @param string $datatype type of the item
   * @param string $itemId id of the item
   * @return boolean true if success, false if failure
   */
  public function deleteItem($datatype, $itemId) {
    $result = $this->client->managementApiCall('DELETE', "{$this->hashid}/items/{$datatype}/{$itemId}");
    return $result['statusCode'] == 204 ;
  }

  /**
   * Delete items in bulk (up to 100)
   *
   * @param  string $datatype type of the items
   * @param  array list of item ids to be deleted
   * @return array assoc array with both items which could be deleted and couldn't
   *         array('errors'=>array(), 'success'=>array('AX01', 'AX02', 'AXFD')
   */
  public function deleteItems($datatype, $itemsIds) {
    $objects = array_map(function($id){return array('id'=>$id);}, $itemsIds);
    $result = $this->client->managementApiCall(
      'DELETE', "{$this->hashid}/items/{$datatype}", null, json_encode($objects)
    );
    return $result['response'];
  }

  /**
   * Obtain stats aggregated data for a certain period.
   *
   * @param DateTime $from_date. Starting date. Default is 15 days ago
   * @param DateTime $to_date. Ending date. Default is today
   *
   * @return ItemsRS iterator through daily aggregated data.
   */
  public function stats($from_date = null, $to_date = null) {
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
  public function topTerms($term, $from_date = null, $to_date = null) {
    if (!in_array($term, array('clicked', 'searches', 'opportunities'))) {
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
  public function process() {
    $result = $this->client->managementApiCall('POST', "{$this->hashid}/tasks/process");
    $taskCreated = ($result['statusCode'] == 201);
    $taskId = $taskCreated ? SearchEngine::obtainId($result['response']['link']) : null;

    return array('task_created' => $taskCreated, 'task_id' => $taskId);
  }

  /**
   * Obtain info of the last processing task sent to the server
   *
   * @return array Assoc array with 'state' and 'message' indicating status of the
   *               last asked processing task
   */
  public function processInfo() {
    $result = $this->client->managementApiCall('GET', "{$this->hashid}/tasks/process");
    unset($result['response']['task_name']);
    return $result['response'];
  }

  /**
   * Obtain info about how a task is going or its result
   *
   * @return array Assoc array with 'state' and 'message' indicating the status
   *               of the task
   */
  public function taskInfo($taskId) {
    $result = $this->client->managementApiCall('GET', "{$this->hashid}/tasks/{$taskId}");
    unset($result['response']['task_name']);
    return $result['response'];
  }

  /**
   * Obtain logs of the latest feed processing tasks done
   *
   * @return array list of arrays representing the logs
   */
  public function logs() {
    $result = $this->client->managementApiCall("GET", "{$this->hashid}/logs");
    return $result['response'];
  }

  /**
   * Extracts identificator from an item or task url.
   *
   * @param string $url item or task resource locator
   * @return the item identificator
   */
  private static function obtainId($url) {
    preg_match('~/\w{32}/(items/\w+|tasks)/([\w-_]+)/?$~', $url, $matches);
    return $matches[2];
  }
}
