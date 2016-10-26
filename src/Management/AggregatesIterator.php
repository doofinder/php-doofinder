<?php

namespace Doofinder\Api\Management;

use Doofinder\Api\Management\SearchEngine;
use Doofinder\Api\Management\ItemsResultSet;

class AggregatesIterator extends ItemsResultSet {

  /**
   * Class to Iterate through SearchEngine's aggregated stats data for a certain period.
   */
  protected $last_page = 0;
  protected $searchParams = array();

  /**
   * @param SearchEngine $searchEngine
   * @param DateTime $from_date . Starting date of the period. Default: 15 days ago
   * @param DateTime $to_date. Ending date of the period. Default: today.
   */
  public function __construct(SearchEngine $searchEngine, $from_date = null, $to_date = null){
    $this->last_page = 0;

    if (!is_null($from_date)) {
      $this->searchParams['from'] = $from_date->format("Ymd");
    }
    if (!is_null($to_date)) {
      $this->searchParams['to'] = $to_date->format("Ymd");
    }

    parent::__construct($searchEngine);
  }

  protected function fetchResultsAndTotal() {
    $params = $this->last_page > 0 ? array("page" => $this->last_page + 1) : array();

    try{
      $apiResponse = $this->searchEngine->client->managementApiCall(
        'GET',
        "{$this->searchEngine->hashid}/stats",
        array_merge($params, $this->searchParams)
      );

      $this->resultsPage = $apiResponse['response']['aggregates'];
      $this->total = $apiResponse['response']['count'];
      $this->last_page++;
      $this->currentItem = each($this->resultsPage);
    } catch (NotFound $nfe) {
      $this->resultsPage = array();
    }

    reset($this->resultsPage);
  }

  public function rewind() {
    $this->last_page = 0;
    parent::rewind();
  }
}
