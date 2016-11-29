<?php

namespace Doofinder\Api\Test;

use Doofinder\Api\Management\ScrollIterator;
use Doofinder\Api\Management\SearchEngine;


class ScrollIteratorTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->client = $this->prophesize('\Doofinder\Api\Management\Client');
        $this->searchEngine = new SearchEngine($this->client->reveal(), 'testHashid', 'test SE');
    }

    public function testIterateThroughAllResults()
    {
        $firstResponse = array(
            "next"=> "someUrl", "count"=>6, "scroll_id"=>"testScroll", "results" => array(
                array("id"=> "id1", "title"=> "title1"),
                array("id"=> "id2", "title"=> "title2"),
                array("id"=> "id3", "title"=> "title3")
            )
        );
        $secondResponse = array(
            "next"=> "someUrl", "count"=>6, "scroll_id"=>"testScroll", "results" => array(
                array("id"=> "id4", "title"=> "title4"),
                array("id"=> "id5", "title"=> "title5"),
                array("id"=> "id6", "title"=> "title6")
            )
        );
        $thirdResponse = array(
            "next"=> "someUrl", "count"=>6, "scroll_id"=>"testScroll", "results" => array()
        );

        // after receiving second request, prepare for third request and send second response
        $reassignMethodSignature = function($args, $client) use ($secondResponse, $thirdResponse) {
            $client->managementApiCall(
                'GET', 'testHashid/items/newType', array('scroll_id' => 'testScroll')
            )
            ->shouldBeCalledTimes(1)
            ->willReturn(array('response'=>$thirdResponse));
            return array('response'=>$secondResponse);
        };

        // first request
        $this->client->managementApiCall('GET', 'testHashid/items/newType', null)
                     ->shouldBeCalledTimes(1)
                     ->willReturn(array('response'=>$firstResponse));

        // second request
        $this->client->managementApiCall(
            'GET', 'testHashid/items/newType', array('scroll_id'=>'testScroll'))
                     ->shouldBeCalledTimes(1)
                     ->will($reassignMethodSignature);

        $items = new ScrollIterator($this->searchEngine, 'newType');

        $counter = 0;
        foreach($items as $item){
            $counter++;
            $this->assertEquals("id$counter",$item['id']);
        }

    }
}