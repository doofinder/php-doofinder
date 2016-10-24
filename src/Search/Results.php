<?php
namespace Doofinder\Api\Search;

/**
 * @author JoeZ99 <jzarate@gmail.com>
 * @author carlosescri <carlosescri@gmail.com>
 *
 * Results
 *
 * Very thin wrapper of the results obtained from the doofinder server
 * it holds two accessors:
 *
 * - getProperty :  get single property of the search results (rpp, page, etc..)
 * - getResults:    get an array with the results
 */
class Results {

  // doofinder status
  const SUCCESS = 'success';      // everything ok
  const NOTFOUND = 'notfound';    // no account with the provided hashid found
  const EXHAUSTED = 'exhausted';  // the account has reached its query limit

  private $properties = array();
  private $results = array();
  private $facets = array();
  private $filter = array();

  public $status = null;

  /**
   * Constructor
   *
   * @param string $jsonResponse JSON returned by the Doofinder search server
   */
  public function __construct($jsonResponse) {
    $response = json_decode($jsonResponse, true);

    foreach ($response as $key => $value){
      if (!is_array($value)) {
        $this->properties[$key] = $value;
      }
    }

    // Status
    if (isset($this->properties['doofinder_status'])) {
      $this->status = $this->properties['doofinder_status'];
    } else {
      $this->status = self::SUCCESS;
    }

    // Results
    if (isset($response['results'])) {
      $this->results = $response['results'];
    }

    // Build a "friendly" filters array
    if (isset($response['filter'])) {
      foreach($response['filter'] as $filterType => $filters) {
        foreach($filters as $filterName => $filterProperties) {
          $this->filter[$filterName] = $filterProperties;
        }
      }
    }

    // facets
    if (isset($response['facets'])) {
      $this->facets = $response['facets'];
    }

    // mark "selected" true or false according to filters presence
    foreach ($this->facets as $name => $properties) {
      switch (true) {
        case isset($properties['terms']):
          foreach($properties['terms']['buckets'] as $idx => $bucket) {
            $this->facets[$name]['terms']['buckets'][$idx]['selected'] = isset($this->filter[$name]) &&
              in_array($bucket['key'], $this->filter[$name]);
          }
          break;
        case isset($properties['range']):
          foreach(array_keys($properties['range']['buckets']) as $idx) {
            $this->facets[$name]['range']['buckets'][$idx]['selected_from'] = isset($this->filter[$name]['gte']) ? $this->filter[$name]['gte'] : false;
            $this->facets[$name]['range']['buckets'][$idx]['selected_to'] = isset($this->filter[$name]['lte']) ? $this->filter[$name]['lte'] : false;
          }
          break;
      }
    }
  }

  /**
   * getProperty
   *
   * get single property from the results
   * @param string $name: 'results_per_page', 'query', 'max_score', 'page', 'total', 'hashid'
   * @return mixed the value of the property
   */
  public function getProperty($name) {
    return array_key_exists($name, $this->properties) ? $this->properties[$name] : null;
  }

  /**
   * getResults
   *
   * @return array search results. at the moment, only the 'cooked' version.
   *                   Each result is of the form:
   *                     array('header'=>...,
   *                           'body' => ..,
   *                           'price' => ..,
   *                           'href' => ...,
   *                           'image' => ...,
   *                           'type' => ...,
   *                           'id' => ..)
   */
  public function getResults(){
    return $this->results;
  }

  /**
   *
   * getFacetsNames
   *
   * @return array facets names.
   */
  public function getFacetsNames(){
    return array_keys($this->facets);
  }

  /**
   * getFacet
   *
   * @param string name the facet name whose results are wanted
   *
   * @return array facet search data
   *                - for terms facets
   *                array(
   *                    '_type'=> 'terms',  // type of facet 'terms' or 'range'
   *                    'missing'=> 3, // # of elements with no value for this facet
   *                    'others'=> 2, // # of terms not present in the search response
   *                    'total'=> 6, // # number of possible terms for this facet
   *                    'terms'=> array(
   *                        array('count'=>6, 'term'=>'Blue', 'selected'=>false), // in the response, there are 6 'blue' terms
   *                        array('count'=>3, 'term': 'Red', 'selected'=>true), // if 'selected'=>true, that term has been selected as filter
   *                        ...
   *                    )
   *                )
   *                - for range facets
   *                array(
   *                    '_type'=> 'range',
   *                    'ranges'=> array(
   *                        array(
   *                              'count'=>6, // in the response, 6 elements within that range.
   *                              'from':0,
   *                              'min': 30
   *                              'max': 90,
   *                              'mean'=>33.2,
   *                              'total'=>432,
   *                              'total_count'=>6,
   *                              'selected_from'=> 34.3 // if present. this value has been used as filter. false otherwise
   *                              'selected_to'=> 99.3 // if present. this value has been used as filter. false otherwise
   *                        ),
   *                        ...
   *                    )
   *                )
   *
   *
   */
  public function getFacet($facetName){
    return $this->facets[$facetName];
  }

  /**
   * getFacets
   *
   * get the whole facets associative array:
   *                array('color'=>array(...), 'brand'=>array(...))
   * each array is defined as in getFacet() docstring
   *
   * @return array facets assoc. array
   */
  public function getFacets(){
    return $this->facets;
  }

  /**
   * getAppliedFilters
   *
   * get the filters the query has defined
   *                    array('categories' => array(  // filter name . same as facet name
   *                             'Sillas de paseo',   // if simple array, it's a terms facet
   *                             'Sacos sillas de paseo'
   *                             ),
   *                           'color' => array(
   *                              'red',
   *                              'blue'
   *                              ),
   *                           'price' => array(
   *                              'include_upper'=>true, // if 'from' , 'to' keys, it's a range facet
   *                              'from'=>35.19,
   *                              'to'=>9999
   *                              )
   *                         )
   *   MEANING OF THE EXAMPLE FILTER:
   *   "FROM the query results, filter only results that have ('Sillas de paseo' OR 'Sacos sillas de paseo') categories
   *   AND ('red' OR 'blue') color AND price is BETWEEN 34.3 and 99.3"

   */
  public function getAppliedFilters(){
    return $this->filter;
  }

  /**
   * isOk
   *
   * checks if all went well
   * @return boolean true if the status is 'success'.
   *                 false if the status is not.
   */
  public function isOk() {
    return $this->status == self::SUCCESS;
  }
}
