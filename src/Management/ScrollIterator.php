<?php

namespace Doofinder\Api\Management;

use Doofinder\Api\Management\SearchEngine;
use \Doofinder\Api\Management\ItemsResultSet;

/**
 * ScrollIterator
 *
 * Class to Iterate/Scroll through search engine's indexed items of a certain datatype
 */
class ScrollIterator extends ItemsResultSet {

  private $scrollId = null;
  private $datatype = null;

  /**
   * @param SearchEngine $searchEngine
   * @param string $datatype type of item . i.e. 'product'
   */
  public function __construct(SearchEngine $searchEngine, $datatype){
    $this->datatype = $datatype;
    parent::__construct($searchEngine);
  }

  /**
   * Loads net next batch of api results
   *
   */
  protected function fetchResultsAndTotal(){
    $apiResults = $this->searchEngine->client->managementApiCall(
      'GET',
      "{$this->searchEngine->hashid}/items/{$this->datatype}",
      ($this->scrollId ? array("scroll_id" => $this->scrollId) : null)
    );
    $this->total = $apiResults['response']['count'];
    $this->scrollId = $apiResults['response']['scroll_id'];
    $this->resultsPage = $apiResults['response']['results'];
    $this->currentItem = each($this->resultsPage);

    reset($this->resultsPage);
  }

  public function rewind(){
    $this->scrollId = null;
    parent::rewind();
  }
}
