<?php
namespace Doofinder\Search;

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

    // Redirections
    if (isset($response['redirection'])) {
      $this->properties['redirection'] = $response['redirection'];
    }

    // Banner
    if(isset($response['banner'])) {
      $this->properties['banner'] = $response['banner'];
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
   * getLegacyFacet
   *
   * @param string name the facet name whose results are wanted
   *
   * @return array facet search data raw from the search server
   *
   *                - for terms facets
   *                array(
   *                    "doc_count" => 1819, // # number of possible terms for this facet
   *                    'terms'=> array(
   *                        "buckets" => array(
   *                            array(
   *                              "key"=>"France",
   *                              "doc_count"=> 1653,
   *                              "selected" => true // only if this term is selected by user.
   *                            ),
   *                            array("key"=>"Spain", "doc_count"=> 332)
   *                        ),
   *                        ....
   *                    )
   *
   *                - for range facets
   *                array(
   *                    "doc_count" => 4482,
   *                    'range'=> array(
   *                        "buckets" => array(
   *                            array(
   *                              "key"=>"0.0-",
   *                              "from"=> 0, "from_as_string"=> "0,0","doc_count"=> 165,
   *                              "stats" => array(
   *                                "count" => 165,
   *                                "min" => 0,
   *                                "max" => 23.2,
   *                                'selected_from'=> 34.3 // if present,
   *                                                       // this value has been used as filter.
   *                                                       // false otherwise
   *                                'selected_to'=> 99.3 // if present.
   *                                                     // this value has been used as filter.
   *                                                     // false otherwise
   *                                ...
   *                              )
   *                            )
   *                          )
   *                        )
   *
   *
   */
  public function getLegacyFacet($facetName){
    return $this->facets[$facetName];
  }

  /**
   * getFacet
   *
   * @param string name the facet whose results are wanted
   *
   * @return array facet inforation
   *
   *                - for term facets
   *                array(
   *                  'count' => 232,
   *                  'terms' => array(
   *                    array(
   *                      'term' => 'France',
   *                      'count' => 23,
   *                      'selected' => false // if previously selected as a filter
   *                    ),
   *                    array('term' => 'Spain', 'count' => 2, 'selected' => true),
   *                    ...
   *                  )
   *                - for range facets
   *                array(
   *                  'count' => 232,
   *                  'from' => 23.2,
   *                  'to' => 443.1,
   *                  'selected_from' => 44, // if se;ected as filter. false otherwise
   *                  'selected_to' => 401 // if selected as filter
   *                )
   */
  public function getFacet($facetName){
    $facetProperties = $this->facets[$facetName];
    switch(true) {
      case isset($facetProperties['terms']):
        return array(
          "count" => $facetProperties['doc_count'],
          'terms' => array_map(
            function($el) {
              return array(
                'term' => $el['key'],
                'count' => $el['doc_count'],
                'selected' => isset($el['selected'])
              );
            },
            $facetProperties['terms']['buckets'])
        );
      case isset($facetProperties['range']):
        $bucket = $facetProperties['range']['buckets'][0];
        return array(
          'count' => $bucket['stats']['count'],
          'from' => $bucket['stats']['min'],
          'to' => $bucket['stats']['max'],
          'selected_from' => isset($bucket['selected_from']) ? $bucket['selected_from'] : false,
          'selected_to' => isset($bucket['selected_to']) ? $bucket['selected_to'] : false
        );
      default:
        break;
    }

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
