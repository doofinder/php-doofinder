<?php

namespace Doofinder\Api\Test;

use Doofinder\Api\Search\Results;

class ResultsTest extends \PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $this->rawFacets = array(
      'price' => array(
        "doc_count" => 30,
        'range' => array(
          'buckets' => array(
            array(
              "key" => "0.0-*",
              "from" => 0,
              "from_as_string" => "0.0",
              "stats" => array(
                "count" => 30,
                "min" => 12,
                "max" => 344.1,
                "avg" => 100,
                "sum" => 1823
              )
            )
          )
        )
      ),
      'color' => array(
        'doc_count' => 6,
        'missing' => array('doc_count' => 0),
        'terms' => array(
          'buckets' =>  array(
            array('key'=>'red', 'doc_count'=>344), array('key'=>'blue', 'doc_count'=>1)
          )
        ),
        'total' => array('value'=>6)
      )
    );

    $this->response = json_encode(
      array(
        "page" => 2, "total" => 44, "results_per_page" => 12, "query" => "ab",
        "hashid" => 'testHashid', 'max_score' => 2.23, 'query_name' => 'match_and',
        'results' => array(
          array('id'=>'id1', 'title' => 't1', 'cats'=>array('cat1', 'cat2')),
          array('id'=>'id2', 'title' => 't2', 'cats'=>array('cat1', 'caxnt2')),
          array('id'=>'id3', 'title' => 't3', 'cats'=>array('cat1ab', 'cat2'))
        ),
        'facets' => $this->rawFacets,
        'filter' => array(
          'range' => array('price'=>array('gte'=>22)),
          'terms' => array('color' => array('red'))
        )
      )
    );

    $this->results = new Results($this->response);
  }

  public function testGetProperty()
  {
    $this->assertEquals('match_and', $this->results->getProperty('query_name'));
    $this->assertEquals(2.23, $this->results->getProperty('max_score'));
  }

  public function testGetResults()
  {
    $res = array(
      array('id'=>'id1', 'title' => 't1', 'cats'=>array('cat1', 'cat2')),
      array('id'=>'id2', 'title' => 't2', 'cats'=>array('cat1', 'caxnt2')),
      array('id'=>'id3', 'title' => 't3', 'cats'=>array('cat1ab', 'cat2'))
    );
    $this->assertEquals($res, $this->results->getResults());
  }

  public function testGetFacetsNames()
  {
    $this->assertEquals(array('price', 'color'), $this->results->getFacetsNames());
  }

  public function testGetFacet()
  {
    $this->assertEquals(
      array(
        'count' => 30,
        'from' => 12,
        'to' => 344.1,
        'selected_from' => 22,
        'selected_to' => false
      ),
      $this->results->getFacet('price')
    );
  }

  public function testGetFacetsAndMarkFacetAsSelected()
  {
    $expectedFacets = $this->rawFacets;
    $expectedFacets['price']['range']['buckets'][0]['selected_from'] = 22;
    $expectedFacets['price']['range']['buckets'][0]['selected_to'] = false;
    $expectedFacets['color']['terms']['buckets'][0]['selected'] = true; // red
    $expectedFacets['color']['terms']['buckets'][1]['selected'] = false; // blue
    $this->assertEquals($expectedFacets, $this->results->getFacets());
  }

  public function testGetAppliedFilters()
  {
    $this->assertEquals(
      array('color'=>array('red'), 'price'=>array('gte'=>22)), $this->results->getAppliedFilters()
    );
  }

}