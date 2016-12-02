<?php

namespace Doofinder\Api\Test;

use phpmock\phpunit\PHPMock;


use Doofinder\Api\Search\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{

  use PHPMock;

  public function setUp()
  {
    $this->testHashid = 'ffffffffffffffffffffffffffffffff';
    $this->searchUrl = "https://eu1-search.doofinder.com/5/search";
    $this->optionsUrl = "https://eu1-search.doofinder.com/5/options";
    $nameSpace = 'Doofinder\API\Search';
    $this->curl_init = $this->getFunctionMock($nameSpace, "curl_init");
    $this->curl_setopt = $this->getFunctionMock($nameSpace, "curl_setopt");
    $this->curl_getinfo = $this->getFunctionMock($nameSpace, "curl_getinfo");
    $this->curl_exec = $this->getFunctionMock($nameSpace, "curl_exec");
    $this->curl_close = $this->getFunctionMock($nameSpace, 'curl_close');
    $this->curl_getinfo->expects($this->any())->willReturn(200);
    $this->curl_setopt->expects($this->any());
//
    $this->client = new Client($this->testHashid, 'eu1-testApiToken');
  }

  public function testAuthHeadersAreSent()
  {
    $this->curl_exec->expects($this->any())->willReturn(json_encode(array()));
    $this->curl_setopt->expects($this->any())->willReturnCallback(
      function($session, $option, $value){
        if($option == CURLOPT_HTTPHEADER){
          $this->assertEquals($value, array('Expect:', 'Authorization: testApiToken'));
        }
      }
    );


    $this->client->getOptions();
  }

  public function testGetOptions(){
    $this->curl_exec->expects($this->any())->willReturn(json_encode(array()));
    $this->curl_init->expects($this->once())->with(
      $this->optionsUrl.'/'.$this->testHashid.'?hashid='.$this->testHashid
    )->willReturn(332);

    $this->client->getOptions();
  }

  public function testBasicAndMatchAllQuery()
  {
    $this->curl_exec->expects($this->any())->willReturn(json_encode(array()));
    // basic query
    $basicQueryUrl = $this->searchUrl.'?query=ab&hashid='.$this->testHashid;
    // match_all query
    $matchAllQueryUrl = $this->searchUrl.'?query_name=match_all&hashid='.$this->testHashid;
    $this->curl_init->expects($this->exactly(2))
                    ->withConsecutive(
                      array($basicQueryUrl), array($matchAllQueryUrl)
                    )
                    ->willReturn(333);

    // basic query
    $this->client->query('ab');
    // match query
    $this->client->query();
  }

  public function testFilteredQuery()
  {
    $this->curl_exec->expects($this->any())->willReturn(json_encode(array()));
    // filtered query
    $searchParams = array(
      'filter'=>array(
        'color'=>array('rojo', 'verde')
      ),
      'query_name'=>'match_and'
    );
    $filteredQueryUrl = $this->searchUrl.'?query=ab&'.http_build_query($searchParams).
      '&hashid='.$this->testHashid;
    $this->curl_init->expects($this->once())->with($filteredQueryUrl)->willReturn(33);
    $this->client->query('ab', null, $searchParams);
  }

  public function testRequeryWhenFilterAndNoQueryName()
  {
    $this->curl_exec->expects($this->any())
                    ->willReturn(json_encode(array('query_name'=>'test_queryname')));
    // filtered query
    $searchParams = array(
      'filter'=>array(
        'color'=>array('rojo', 'verde')
      )
    );
    $preQueryUrl = $this->searchUrl.'?query=ab&hashid='.$this->testHashid;
    $filteredQueryUrl = $this->searchUrl.'?query=ab&query_name=test_queryname&'
      .http_build_query($searchParams).'&hashid='.$this->testHashid;
    $this->curl_init->expects($this->exactly(2))
                    ->withConsecutive(array($preQueryUrl), array($filteredQueryUrl));
    $this->client->query('ab', null, $searchParams);
  }

  public function testAnyOptionGoesToURL()
  {
    $this->curl_exec->expects($this->once())
                    ->willReturn(json_encode(array('query_name'=>'test_queryname')));
    $searchQueryUrl= $this->searchUrl.'?query=ab&kiko=pepito&hashid='.$this->testHashid;
    $this->curl_init->expects($this->once())->with($searchQueryUrl);
    $this->client->query('ab', null, array('kiko'=>'pepito'));
  }

  public function testPageParameter()
  {
    $this->curl_exec->expects($this->once())
                    ->willReturn(json_encode(array('query_name'=>'tq')));
    $pagedQueryUrl = $this->searchUrl.'?query=ab&page=2&hashid='.$this->testHashid;
    $this->curl_init->expects($this->once())->with($pagedQueryUrl);
    $this->client->query('ab', 2);
  }

  public function testSanitizeEmptyValues()
  {
    $this->curl_exec->expects($this->once())
                    ->willReturn(json_encode(array()));
    $dirty = array('color'=>array('red', 'brand'=>''));
    $clean = array('color'=>array('red'));
    $cleanFilters = array('filter'=>$clean);
    $dirtyFilters = array('filter'=>$dirty);
    $cleanSParams = array_merge($cleanFilters, array('query_name'=>'a'));
    $dirtySParams = array_merge($dirtyFilters, array('query_name'=>'a'));
    // no mention of 'brand' in url
    $sanitizedQueryUrl = $this->searchUrl.'?query=ab&'.http_build_query($cleanSParams)
                                                      ."&hashid={$this->testHashid}";
    $this->curl_init->expects($this->once())->with($sanitizedQueryUrl);
    $this->client->query('ab', null, $dirtySParams);
  }

  public function testUpdateRangeFilterKeys()
  {
    $this->curl_exec->expects($this->once())->willReturn(json_encode(array()));
    $cleanParams = array('filter'=>array('price'=>array('gte'=>2, 'lte'=>3)), 'query_name'=>'a');
    $dirtyParams = array('filter'=>array('price'=>array('from'=>2, 'to'=>3)), 'query_name'=>'a');
    $queryUrl = $this->searchUrl.'?query=ab&'.http_build_query($cleanParams)
                                             ."&hashid={$this->testHashid}";
    $this->curl_init->expects($this->once())->with($queryUrl);
    $this->client->query('ab', null, $dirtyParams);
  }

  public function testHasNextAndPrev()
  {
    $counter = 0;
    $this->curl_exec->expects($this->exactly(4))->willReturnCallback(function() use(&$counter) {
        $counter++;
        echo $counter;
        switch($counter){
          case 1:
            // first request. has next page
            return json_encode(array('total'=>44, 'page'=>2));
          case 2:
            // second request. doesn't have next page
            return json_encode(array('total'=>44, 'page'=>44));
          case 3:
            // third request. have prev
            return json_encode(array('total'=>44, 'page'=>2));
          case 4:
            // fourth request. does not have prev
            return json_encode(array('total'=>44, 'page'=>1));
        }
      });

    $this->client->query('ab');// first request
    $this->assertTrue($this->client->hasNext());
    $this->client->query('ab');// second request
    $this->assertFalse($this->client->hasNext());
    $this->client->query('ab');// third request
    $this->assertTrue($this->client->hasPrev());
    $this->client->query('ab');// fourth request
    $this->assertFalse($this->client->hasPrev());
  }

  public function testSetFilter()
  {
    $this->curl_exec->expects($this->any())->willReturn(json_encode(array()));
    $searchParams = array(
      'filter'=>array(
        'color'=>array('rojo', 'verde')
      )
    );
    $filteredQueryUrl = $this->searchUrl.'?'.http_build_query($searchParams).'&query=ab&query_name=test_queryname&hashid='.$this->testHashid;
    $this->curl_init->expects($this->once())->with($filteredQueryUrl);
    $this->client->setFilter('color', array('rojo', 'verde'));
    $this->client->query('ab', null, array('query_name'=>'test_queryname'));
  }

  public function testGetFilter()
  {
    $this->curl_exec->expects($this->any())->willReturn(json_encode(array()));
    $searchParams = array(
      'filter'=>array('color'=>array('rojo', 'verde')),
      'query_name'=>'a'
    );
    $this->client->query('ab', null, $searchParams);
    $this->assertEquals($searchParams['filter']['color'], $this->client->getFilter('color'));
  }

  public function testGetFilters()
  {
    $this->curl_exec->expects($this->any())->willReturn(json_encode(array()));
    $sParams = array('filter'=>array('color'=>array('red'), 'brand'=>array('nike')),
                     'query_name'=>'a');
    $this->client->query('ab', null, $sParams);
    $this->assertEquals(array('color'=>array('red'), 'brand'=>array('nike')),
                        $this->client->getFilters());
  }

  public function testAddSort()
  {
    $this->curl_exec->expects($this->any())->willReturn(json_encode(array()));
    $sortedQueryUrl = $this->searchUrl.'?'
      .http_build_query(array('sort'=>array(array('rice'=>'desc'))))
      ."&query=ab&hashid={$this->testHashid}";
    $this->curl_init->expects($this->once())->with($sortedQueryUrl);
    $this->client->addSort('rice', 'desc');
    $this->client->query('ab');
  }



}