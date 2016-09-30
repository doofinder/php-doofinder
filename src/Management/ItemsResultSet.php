<?php

namespace Doofinder\Api\Management;

/**
 * Helper class to iterate through the search engine's items
 *
 * Implemets Iterator interface so foreach() can work with ItemRS
 * It's supposed to be extended
 */
class ItemsResultSset implements \Iterator {

  protected $searchEngine = null;
  protected $resultsPage = null;
  protected $position = 0;
  protected  $total = null;

  function __construct($searchEngine) {
    $this->searchEngine = $searchEngine;
  }

  protected function fetchResultsAndTotal(){
    /**
     * Function to be implemented in children
     *
     **/
    throw new Exception('Not implemented method');
  }

  function rewind(){
    $this->position = 0;
    $this->total = null;
    $this->resultsPage = null;
    $this->fetchResultsAndTotal();
    $this->currentItem = each($this->resultsPage);
  }

  function valid(){
    return $this->position < $this->total;
  }

  function current(){
    return $this->currentItem['value'];
  }

  function key(){
    return $this->position;
  }

  function next(){
    ++$this->position;
    $this->currentItem = each($this->resultsPage);
    if(!$this->currentItem && $this->position < $this->total){
      $this->fetchResultsAndTotal();
      $this->currentItem = each($this->resultsPage);
    }
  }
}
