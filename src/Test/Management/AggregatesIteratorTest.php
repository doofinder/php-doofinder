<?php

namespace Doofinder\Api\Test;

use Doofinder\Api\Management\AggregatesIterator;
use Doofinder\Api\Management\SearchEngine;


class AggregatesIteratorTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->client = $this->prophesize('\Doofinder\Api\Management\Client');
        $this->searchEngine = new SearchEngine($this->client->reveal(), 'testHashid', 'test SE');
    }

    public function testIterateThroughAllAggregates()
    {
        $firstResponse = array(
            "next"=> "someUrl", "count"=>4, "previous"=>null, "aggregates" => array(
                array("date"=> "2006-10-20", "searches"=> 1, "requests"=>33),
                array("date"=> "2006-10-21", "searches"=> 2, "requests"=>34)
            )
        );
        $secondResponse = array(
            "next"=> "someUrl", "count"=>4, "previous"=>null, "aggregates" => array(
                array("date"=> "2006-10-21", "searches"=> 3, "requests"=>33),
                array("date"=> "2006-10-22", "searches"=> 4, "requests"=>34)
            )
        );

        $thirdResponse = array(
            "next"=> "someUrl", "count"=>6, "previous"=>null, "aggregates" => array()
        );

        // after receiving second request, prepare for third request and send second response
        $prepareForThirdRequest = function($args, $client) use ($secondResponse, $thirdResponse) {
            $client->managementApiCall(
                'GET', 'testHashid/stats',
                array('from'=>'20161020', 'to'=>'20161023', 'page'=>2)
            )
                ->shouldBeCalledTimes(1)
            ->willReturn($thirdResponse);
            return array('response'=>$secondResponse);
        };

        // after receiving first request, prepare for third request and send second response
        $prepareForSecondRequest = function($args, $client) use ($prepareForThirdRequest, $firstResponse) {
            $client->managementApiCall(
                'GET', 'testHashid/stats',
                array('from'=>'20161020', 'to'=>'20161023', 'page'=>2)
            )
                ->shouldBeCalledTimes(1)
            ->will($prepareForThirdRequest);
            return array('response'=>$firstResponse);
        };

        // first request
        $this->client->managementApiCall(
            'GET', 'testHashid/stats', array('from'=>'20161020', 'to'=>'20161023')
        )
                     ->shouldBeCalledTimes(1)
                     ->will($prepareForSecondRequest);


        $aggregates = new AggregatesIterator(
            $this->searchEngine, new \Datetime('2016-10-20'), new \Datetime('2016-10-23')
        );

        $counter = 0;
        foreach($aggregates as $agg){
            $counter++;
            $this->assertEquals($counter, $agg['searches']);
        }
    }

}