<?php

namespace Doofinder\Api\Management;

use Doofinder\Api\Management\SearchEngine;
use Doofinder\Api\Management\AggregatesIterator;
use Doofinder\Api\Management\Errors\NotProcessedResponse;
use Doofinder\Api\Management\Errors\MotFound;

class TopTermsIterator extends AggregatesIterator {

  /**
   * Class to Iterate through SearchEngine's top terms stats data for a certain period.
   */
  private $term = null; // type of term: 'clicked', 'searches', 'opportunities'

  /**
   * Constructor
   *
   * @param SearchEngine $searchEngine
   * @param DateTime $from_date . Starting date of the period. Default: 15 days ago
   * @param DateTime $to_date. Ending date of the period. Default: today.
   * @param string term. type of term: 'clicked', 'searches', 'opportunities'
   */
  public function __construct(SearchEngine $searchEngine, $term, $from_date = null, $to_date = null) {
    $this->term = $term;
    parent::__construct($searchEngine, $from_date, $to_date);
  }

  protected function fetchResultsAndTotal() {
    $params = $this->last_page > 0 ? array("page" => $this->last_page + 1) : array();
    try {
      $apiResponse = $this->searchEngine->client->managementApiCall(
        'GET',
        "{$this->searchEngine->hashid}/stats/top_{$this->term}",
        array_merge($params, $this->searchParams)
      );

      // still generating?
      if ($apiResponse['statusCode'] == 202) {
        throw new NotProcessedResponse("Your request is still being processed. Please try again later");
      }

      $this->resultsPage = $apiResponse['response'][$this->term];
      $this->total = $apiResponse['response']['count'];
      $this->last_page++;
      $this->currentItem = each($this->resultsPage);
    } catch (NotFound $nfe) {
      $this->resultsPage = array();
    }

    reset($this->resultsPage);
  }
}
