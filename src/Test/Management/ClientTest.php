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

}