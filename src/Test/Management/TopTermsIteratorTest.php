<?php

namespace Doofinder\Api\Test;

use Doofinder\Api\Management\TopTermsIterator;
use Doofinder\Api\Management\SearchEngine;


class TopTermsIteratorTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->client = $this->prophesize('\Doofinder\Api\Management\Client');
        $this->searchEngine = new SearchEngine($this->client->reveal(), 'testHashid', 'test SE');
    }

    /**
     * @expectedException \Doofinder\Api\Management\Errors\NotProcessedResponse
     */
    public function testNotProcessedResponse()
    {
        $this->client->managementApiCall('GET', "testHashid/stats/top_fakeTerm", [])
                     ->shouldBeCalledTimes(1)
                     ->willReturn(array('statusCode'=>202));

        $terms = new TopTermsIterator($this->searchEngine, 'fakeTerm');
        foreach($terms as $t){
            echo "boo";
        }
    }

    /**
     * @param string $termName
     *
     * @dataProvider providerTermNames
     */
    public function testIterateThroughAllTopTerms($termName)
    {
        $firstResponse = array(
            "next"=> "someUrl", "count"=>3, "previous"=>null,
            "start"=> '2016-10-20', 'end'=> '2016-10-23',
            $termName => array(
                array("term"=> "t1", "count"=> 1),
                array("term"=> "t2", "count"=> 1)
            )
        );
        $secondResponse = array(
            "next"=> "someUrl", "count"=>3, "previous"=>null,
            "start"=> '2016-10-20', 'end'=> '2016-10-23',
            $termName => array(
                array("term"=> "t3", "count"=> 1)
            )
        );

        // after receiving first request, prepare for second request and send first response
        $prepareForSecondRequest = function($args, $client) use (
            $termName, $secondResponse, $firstResponse
        ) {
            $client->managementApiCall(
                'GET', "testHashid/stats/top_$termName",
                array('from'=>'20161020', 'to'=>'20161023', 'page'=>2)
            )
                ->shouldBeCalledTimes(1)
            ->willReturn(array('response'=>$secondResponse, 'statusCode'=>200));
            return array('response'=>$firstResponse, 'statusCode'=>200);
        };

        // first request
        $this->client->managementApiCall(
            'GET', "testHashid/stats/top_$termName", array('from'=>'20161020', 'to'=>'20161023')
        )
                     ->shouldBeCalledTimes(1)
                     ->will($prepareForSecondRequest);


        $terms = new TopTermsIterator(
            $this->searchEngine, $termName, new \Datetime('2016-10-20'), new \Datetime('2016-10-23')
        );

        $counter = 0;
        foreach($terms as $term){
            $counter++;
            $this->assertEquals("t$counter", $term['term']);
        }
    }

    public function providerTermNames()
    {
        return array(
            array('searches'),
            array('clicked'),
            array('opportunities')
        );
    }

}