<?php

namespace Doofinder\Api\Test;

use Doofinder\Api\Search\Results;

class ResultsTest extends \PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $this->response = json_encode(
      array(
        "page" => 2, "total" => 44, "results_per_page" => 12, "query" => "ab",
        "hashid" => 'testHashid', 'max_score' => 2.23, 'query_name' => 'match_and',
        'results' => array(
          array('id'=>'id1', 'title' => 't1', 'cats'=>array('cat1', 'cat2')),
          array('id'=>'id2', 'title' => 't2', 'cats'=>array('cat1', 'caxnt2')),
          array('id'=>'id3', 'title' => 't3', 'cats'=>array('cat1ab', 'cat2'))
        ),
        'facets' => array(
          'price' => array('type'=>'range'),
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
        ),
        'filter' => array(
          'terms' => array('color'=>array('red'))
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
    $this->assertEquals(array('type'=>'range'), $this->results->getFacet('price'));
  }

  public function testGetFacetsAndMarkFacetAsSelected()
  {
    $facets = array(
      'price' => array('type'=>'range'),
      'color' => array(
        'doc_count' => 6,
        'missing' => array('doc_count' => 0),
        'terms' => array(
          'buckets' =>  array(
            array('key'=>'red', 'doc_count'=>344, 'selected'=>true),
            array('key'=>'blue', 'doc_count'=>1, 'selected'=>false)
          )
        ),
        'total' => array('value'=>6)
      )
    );
    $this->assertEquals($facets, $this->results->getFacets());
  }

  public function testGetAppliedFilters()
  {
    $this->assertEquals(
      array('color'=>array('red')), $this->results->getAppliedFilters()
    );
  }

}