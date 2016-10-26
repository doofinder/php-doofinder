<?php

namespace Doofinder\Api\Management;

use Doofinder\Api\Management\SearchEngine;


/**
 * Helper class to iterate through the search engine's items
 *
 * Implemets Iterator interface so foreach() can work with ItemRS
 * It's supposed to be extended
 */
class ItemsResultSet implements \Iterator {

  protected $searchEngine = null;
  protected $resultsPage = null;
  protected $position = 0;
  protected $total = null;

  public function __construct(SearchEngine $searchEngine) {
    $this->searchEngine = $searchEngine;
  }

  /**
   * To be implemented in children
   */
  protected function fetchResultsAndTotal() {
    throw new Exception('Not implemented.');
  }

  public function rewind() {
    $this->position = 0;
    $this->total = null;
    $this->resultsPage = null;
    $this->fetchResultsAndTotal();
    $this->currentItem = each($this->resultsPage);
  }

  public function valid() {
    return $this->position < $this->total;
  }

  public function current() {
    return $this->currentItem['value'];
  }

  public function key() {
    return $this->position;
  }

  public function next() {
    ++$this->position;
    $this->currentItem = each($this->resultsPage);
    if (!$this->currentItem && $this->position < $this->total) {
      $this->fetchResultsAndTotal();
      $this->currentItem = each($this->resultsPage);
    }
  }
}
