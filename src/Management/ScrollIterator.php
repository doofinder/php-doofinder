<?php

namespace Doofinder\Api\Management;

use \Doofinder\Api\Management\ItemsResultSet;

/**
 * ScrollIterator
 *
 * Class to Iterate/Scroll through search engine's indexed items of a certain datatype
 */
class ScrollIterator extends ItemsResultSet {

  private $scrollId = null;
  private $dType = null;

  /**
   * @param SearchEngine $searchEngine
   * @param string $dType type of item . i.e. 'product'
   */
  function __construct($searchEngine, $dType){
    $this->dType = $dType;
    parent::__construct($searchEngine);
  }

  /**
   * Loads net next batch of api results
   *
   */
  protected function fetchResultsAndTotal(){
    $params = $this->scrollId ? array("scroll_id" => $this->scrollId) : null;
    $apiResults = $this->searchEngine->dma->managementApiCall(
      'GET',
      "{$this->searchEngine->hashid}/items/{$this->dType}",
      $params
    );
    $this->total = $apiResults['response']['count'];
    $this->scrollId = $apiResults['response']['scroll_id'];
    $this->resultsPage = $apiResults['response']['results'];
    $this->currentItem = each($this->resultsPage);
    reset($this->resultsPage);
  }

  function rewind(){
    $this->scrollId = null;
    parent::rewind();
  }
}
