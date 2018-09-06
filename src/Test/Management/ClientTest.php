<?php

namespace Doofinder\Api\Test\Management;

use Doofinder\Api\Management\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{

  protected function setUp()
  {
    $this->client = $this->getMockBuilder('Doofinder\Api\Management\Client')
                       ->setConstructorArgs(array('xx1-testApiKey'))
                       ->setMethods(array('talkToServer'))
                       ->getMock();
  }

  public function testManagementApiCallUrlAndHeaders()
  {
    // right zone-url formation
    // right auth headers
    $this->client->expects($this->once())
               ->method('talkToServer')
               ->with(
                 $this->equalTo('GET'),
                 $this->equalTo('https://xx1-api.doofinder.com/v1/'),
                 $this->equalTo(array("Authorization: Token testApiKey",
                                      'Content-Type: application/json',
                                      'Expect:'))
               )
               ->willReturn(array('statusCode'=>200, 'contentResponse'=>''));

    $this->client->getApiRoot();
  }

  public function testManagementApiCallEntryPointAndParams()
  {
    $expectedUrl = 'https://xx1-api.doofinder.com/v1/testHashid/items/product?foo=bar&cat%5B0%5D=cat1&cat%5B1%5D=cat2';
    // right method, url and url params
    $this->client->expects($this->once())
               ->method('talkToServer')
               ->with(
                 $this->equalTo('GET'),
                 $this->equalTo($expectedUrl),
                 $this->equalTo(array("Authorization: Token testApiKey",
                                      'Content-Type: application/json',
                                      'Expect:'))
               )
                 ->willReturn(array('statusCode'=>200, 'contentResponse'=>''));

    $this->client->managementApiCall(
      'GET', 'testHashid/items/product', array('foo'=>'bar', 'cat'=>array('cat1', 'cat2'))
    );
  }

  public function testManagementApiCallGETMethodAndNoData()
  {
    $expectedUrl = 'https://xx1-api.doofinder.com/v1/testHashid/tasks/';

    // with no POST, no DATA
    $this->client->expects($this->once())
               ->method('talkToServer')
               ->with(
                 $this->equalTo('GET'),
                 $this->equalTo($expectedUrl),
                 $this->equalTo(array("Authorization: Token testApiKey",
                                      'Content-Type: application/json',
                                      'Expect:')),
                 null
               )
                 ->willReturn(array('statusCode'=>200, 'contentResponse'=>''));
    $this->client->managementApiCall('GET', 'testHashid/tasks/', null, array('custom'=>'data'));
  }

  public function testManagementApiCallPOSTMethodAndData(){
    $expectedUrl = 'https://xx1-api.doofinder.com/v1/testHashid/tasks/';
    // with DATA, POST
    $this->client->expects($this->once())
               ->method('talkToServer')
               ->with(
                 $this->equalTo('POST'),
                 $this->equalTo($expectedUrl),
                 $this->equalTo(array("Authorization: Token testApiKey",
                                      'Content-Type: application/json',
                                      'Expect:')),
                 array('custom'=>'data')
               )
                 ->willReturn(array('statusCode'=>200, 'contentResponse'=>''));

    $this->client->managementApiCall('POST', 'testHashid/tasks/', null, array('custom'=>'data'));

  }

  public function testManagementApiCallPATCHMethodAndData(){
    $expectedUrl = 'https://xx1-api.doofinder.com/v1/testHashid/items/idx';
    // with DATA, PATCH
    $this->client->expects($this->once())
               ->method('talkToServer')
               ->with(
                 $this->equalTo('PATCH'),
                 $this->equalTo($expectedUrl),
                 $this->equalTo(array("Authorization: Token testApiKey",
                                      'Content-Type: application/json',
                                      'Expect:')),
                 array('custom'=>'data')
               )
                 ->willReturn(array('statusCode'=>200, 'contentResponse'=>''));

    $this->client->managementApiCall('PATCH', 'testHashid/items/idx', null, array('custom'=>'data'));

  }

  public function testGetSearchEngines(){
    $expectedUrl = 'https://xx1-api.doofinder.com/v1/searchengines?page=';
    $this->client->expects($this->once())
      ->method('talkToServer')
      ->with(
        $this->equalTo('GET'),
        $this->stringContains($expectedUrl),
        $this->equalTo(array("Authorization: Token testApiKey",
                             'Content-Type: application/json',
                             'Expect:')),
        null
      )
      ->willReturn(
        array('statusCode'=>200, 'contentResponse'=>json_encode(array("next"=>null, "results"=>array())))
      );
    $this->client->getSearchEngines();
  }

  public function testGetMoreThan10SearchEngines(){
    $expectedUrl = 'https://xx1-api.doofinder.com/v1/searchengines?page=';
    for($ii=0;$ii<14;$ii++){
      $returnedSearchEngines[] = array("name"=>"se{$ii}", "hashid"=>"hadhid{$ii}");
    }

    $twoResponses = array_chunk($returnedSearchEngines, 10);

    $firstResponse = array(
      'statusCode'=>200,
      'contentResponse'=>json_encode(array('next'=>'something', 'results'=>$twoResponses[0]))
    );

    $secondResponse = array(
      'statusCode'=>200,
      'contentResponse'=>json_encode(array('next'=>null, 'results'=>$twoResponses[1]))
    );
    $this->client->expects($this->exactly(2))
      ->method('talkToServer')
      ->with(
        $this->equalTo('GET'),
        $this->stringContains($expectedUrl),
        $this->equalTo(array("Authorization: Token testApiKey",
                             'Content-Type: application/json',
                             'Expect:')),
        null
      )
      ->will($this->onConsecutiveCalls($firstResponse, $secondResponse));

    $this->client->getSearchEngines();
  }

  public function testGetSearchEngine(){
    $expectedUrl = 'https://xx1-api.doofinder.com/v1/searchengines/testHashid';
    $jsonSearchEngine =json_encode(array('hashid'=>'testHashid', 'name'=>'testName'));
    $this->client->expects($this->once())
      ->method('talkToServer')
      ->with(
        $this->equalTo('GET'), $this->equalTo($expectedUrl),
        $this->equalTo(array("Authorization: Token testApiKey",
                             'Content-Type: application/json',
                             'Expect:')),
        null
      )
      ->willReturn(array('statusCode'=>200, 'contentResponse'=>$jsonSearchEngine));

    $this->client->getSearchEngine('testHashid');
  }

  public function testAddSearchEngine(){
    $expectedUrl = 'https://xx1-api.doofinder.com/v1/searchengines';
    $jsonSearchEngine =json_encode(array('hashid'=>'testHashid', 'name'=>'testName'));
    $this->client->expects($this->once())
      ->method('talkToServer')
      ->with(
        $this->equalTo('POST'), $this->equalTo($expectedUrl),
        $this->equalTo(array("Authorization: Token testApiKey",
                             'Content-Type: application/json',
                             'Expect:')),
        array('name'=>'test', 'site_url'=>'xx')
      )
      ->willReturn(array('statusCode'=>200, 'contentResponse'=>$jsonSearchEngine));

    $this->client->addSearchEngine('test', array('site_url'=>'xx'));
  }

   public function testUpdateSearchEngine(){
    $expectedUrl = 'https://xx1-api.doofinder.com/v1/searchengines/testHashid';
    $jsonSearchEngine =json_encode(array('hashid'=>'testHashid', 'name'=>'testName'));
    $this->client->expects($this->once())
      ->method('talkToServer')
      ->with(
        $this->equalTo('PATCH'), $this->equalTo($expectedUrl),
        $this->equalTo(array("Authorization: Token testApiKey",
                             'Content-Type: application/json',
                             'Expect:')),
        array('site_url'=>'xxmod')
      )
      ->willReturn(array('statusCode'=>200, 'contentResponse'=>$jsonSearchEngine));

    $this->client->updateSearchEngine('testHashid', array('site_url'=>'xxmod'));
  }

    public function testDeleteSearchEngine(){
    $expectedUrl = 'https://xx1-api.doofinder.com/v1/searchengines/testHashid';
    $this->client->expects($this->once())
      ->method('talkToServer')
      ->with(
        $this->equalTo('DELETE'), $this->equalTo($expectedUrl),
        $this->equalTo(array("Authorization: Token testApiKey",
                             'Content-Type: application/json',
                             'Expect:'))
      )
      ->willReturn(array('statusCode'=>204, 'contentResponse'=>''));

    $this->client->deleteSearchEngine('testHashid');
  }


}
