<?php

namespace Doofinder\Api\Test;

use \phpmock\phpunit\PHPMock;


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
        switch($counter){
          case 1:
            // first request (page 2). has next page
            return json_encode(array('total'=>44, 'page'=>2));
          case 2:
            // second request. (last page) doesn't have next page
            return json_encode(array('total'=>44, 'page'=>44));
          case 3:
            // third request. have prev (page 2)
            return json_encode(array('total'=>44, 'page'=>2));
          case 4:
            // fourth request. does not have prev (first page)
            return json_encode(array('total'=>44, 'page'=>1));
        }
      });

    $this->client->query('ab');// page 2
    $this->assertTrue($this->client->hasNext());
    $this->client->query('ab');// last page
    $this->assertFalse($this->client->hasNext());
    $this->client->query('ab');// page 2
    $this->assertTrue($this->client->hasPrev());
    $this->client->query('ab');// first page
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

  public function testNextPage()
  {
    $this->curl_exec->expects($this->any())->willReturn(
      json_encode(array('total'=>44,'page'=>1, 'query'=>'ab'))
    );
    $searchParams = array(
      'filter'=>array('color'=>array('red')),
      'sort'=>array(array('price'=>'desc')),
      'query_name'=>'a',
      'hashid'=>$this->testHashid
    );
    //->query('ab') url
    $page1QueryUrl = $this->searchUrl.'?query=ab&'.http_build_query($searchParams);
    // ->nextPage() url
    $page2QueryUrl = $this->searchUrl.'?query=ab&'.http_build_query($searchParams).'&page=2';
    $this->curl_init->expects($this->exactly(2))
                    ->withConsecutive(array($page1QueryUrl), array($page2QueryUrl));
    $this->client->query('ab', null, $searchParams);
    $this->client->nextPage();
  }

  public function testPrevPage()
  {
    $this->curl_exec->expects($this->any())->willReturn(
      json_encode(array('total'=>44,'page'=>2, 'query'=>'ab'))
    );
    $searchParams = array(
      'filter'=>array('color'=>array('red')),
      'sort'=>array(array('price'=>'desc')),
      'query_name'=>'a',
      'hashid'=>$this->testHashid
    );
    //->query('ab', 2) url
    $page2QueryUrl = $this->searchUrl.'?query=ab&page=2&'.http_build_query($searchParams);
    //->->prevPage() url
    $page1QueryUrl = $this->searchUrl.'?query=ab&page=1&'.http_build_query($searchParams);
    $this->curl_init->expects($this->exactly(2))
                    ->withConsecutive(array($page2QueryUrl), array($page1QueryUrl));
    $this->client->query('ab', 2, $searchParams);
    $this->client->prevPage();
  }

  public function testToQueryString()
  {
    $this->curl_exec->expects($this->any())->willReturn(
      json_encode(
        array('total'=>44, 'page'=>1, 'query' =>'ab', 'query_name'=>'baba')
      )
    );
    $searchParams = array(
      'query_name'=>'baba',
      'filter'=>array('color'=>array('red')),
      'sort'=>array(array('price'=>'desc'))
    );
    $serialization = 'dfParam_query=ab&dfParam_query_name=baba&dfParam_filter%5Bcolor%5D%5B0%5D=red&dfParam_sort%5B0%5D%5Bprice%5D=desc';
    $this->client->query('ab', null, $searchParams);  // do the query to populate state
    $this->assertEquals($serialization, $this->client->toQueryString());
  }

  public function testToQueryStringWithCustomPrefix()
  {
    $this->curl_exec->expects($this->any())->willReturn(
      json_encode(
        array('total'=>44, 'page'=>1, 'query' =>'ab', 'query_name'=>'baba')
      )
    );
    $searchParams = array(
      'query_name'=>'baba',
      'filter'=>array('color'=>array('red')),
      'sort'=>array(array('price'=>'desc'))
    );
    // create client with custom prefix
    $customPrefixClient = new Client(
      $this->testHashid, 'eu1-testApiToken', false, array('prefix' => 'customP')
    );
    $customPrefixserialization = 'customPquery=ab&customPquery_name=baba&customPfilter%5Bcolor%5D%5B0%5D=red&customPsort%5B0%5D%5Bprice%5D=desc';
    $customPrefixClient->query('ab', null, $searchParams);  // do the query to populate state
    $this->assertEquals($customPrefixserialization, $customPrefixClient->toQueryString());
  }

  public function testFromQueryString()
  {
    $this->curl_exec->expects($this->any())->willReturn(
      json_encode(
        array('total'=>44, 'page'=>1, 'query' =>'ab', 'query_name'=>'baba')
      )
    );
    // to fool fromQueryString
    $_REQUEST = array(
      'dfParam_query'=>'ab',
      'dfParam_query_name'=>'baba',
      'dfParam_filter'=> array('color'=>array('red')),
      'dfParam_sort'=>array(array('price'=>'desc'))
    );
    $queryUrl = $this->searchUrl.'?query=ab&query_name=baba&filter%5Bcolor%5D%5B0%5D=red&sort%5B0%5D%5Bprice%5D=desc&hashid='.$this->testHashid;
    $this->curl_init->expects($this->once())->with($queryUrl);
    // unserialize client
    $client = new Client($this->testHashid, 'eu1-testApiToken');
    $client->fromQueryString();
    $client->query();  // do the query
  }

  public function testFromQueryStringWithCustomPrefix()
  {
    $this->curl_exec->expects($this->any())->willReturn(
      json_encode(
        array('total'=>44, 'page'=>1, 'query' =>'ab', 'query_name'=>'baba')
      )
    );
    // to fool fromQueryString
    $_REQUEST = array(
      'customPquery'=>'ab',
      'customPquery_name'=>'baba',
      'customPfilter'=> array('color'=>array('red')),
      'customPsort'=>array(array('price'=>'desc'))
    );
    // query that should be done with params from $_REQUEST
    $queryUrl = $this->searchUrl.'?query=ab&query_name=baba&filter%5Bcolor%5D%5B0%5D=red&sort%5B0%5D%5Bprice%5D=desc&hashid='.$this->testHashid;
    $this->curl_init->expects($this->once())->with($queryUrl);
    // have to create new one here so it can see $_REQUEST
    $client = new Client($this->testHashid, 'eu1-testApiToken', false, array('prefix'=>'customP'));
    $client->fromQueryString(); // unserialize client
    $client->query();  // do the query
  }

  public function testConstructorFromQueryStringWithCustomPrefix(){
    $this->curl_exec->expects($this->any())->willReturn(
      json_encode(
        array('total'=>44, 'page'=>1, 'query' =>'ab', 'query_name'=>'baba')
      )
    );
    // to fool fromQueryString
    $_REQUEST = array(
      'customPquery'=>'ab',
      'customPquery_name'=>'baba',
      'customPfilter'=> array('color'=>array('red')),
      'customPsort'=>array(array('price'=>'desc'))
    );
    $queryUrl = $this->searchUrl.'?query=ab&query_name=baba&filter%5Bcolor%5D%5B0%5D=red&sort%5B0%5D%5Bprice%5D=desc&hashid='.$this->testHashid;
    $this->curl_init->expects($this->once())->with($queryUrl);
    // unserialize client in constructor
    $client = new Client($this->testHashid, 'eu1-testApiToken', true, array('prefix'=>'customP'));

    $client->query();  // do the query
  }




}