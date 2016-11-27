<?php

namespace Doofinder\Api\Test;

use Doofinder\Api\Management\SearchEngine;
use Doofinder\Api\Management\ScrollIterator;
use Doofinder\Api\Management\AggregatesIterator;

class SearchEngineTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        $this->client = $this->prophesize('\Doofinder\Api\Management\Client');
        $this->searchEngine = new SearchEngine($this->client->reveal(), 'testHashid', 'test SE');
    }

    public function testGetTypesApiCall()
    {
        // Set up the expectation for the managemetApiCall() method
        // to be called only once and with the strings 'GET' and 'testHashid/types'
        // as its parameters.
        $this->client->managementApiCall('GET', 'testHashid/types')->shouldBeCalledTimes(2);

        $this->searchEngine->getTypes();
        $this->searchEngine->getDataTypes();
    }

    public function testAddTypesApiCall()
    {
        $this->client->managementApiCall(
            'POST',
            'testHashid/types',
            null,
            json_encode(array('name'=>'newType'))
        )->shouldBeCalledTimes(1);

        $this->searchEngine->addType('newType');
    }

    public function testDeleteTypeApiCall()
    {
        $this->client->managementApiCall(
            'DELETE',
            'testHashid/types/newType'
        )->shouldBeCalledTimes(1);

        $this->searchEngine->deleteType('newType');
    }

    public function testItemsReturnsScrollIterator()
    {
        $scrollIterator = $this->searchEngine->items('newType');
        // returns a ScrollIterator
        $this->assertInstanceOf('\Doofinder\Api\Management\ScrollIterator', $scrollIterator);
        // for the right Datatype
        $this->assertEquals('newType', $this->exposeAttribute($scrollIterator, 'datatype'));
    }

    public function testGetItemApiCall()
    {
        $this->client->managementApiCall(
            'GET',
            'testHashid/items/newType/itemId'
        )->shouldBeCalledTimes(1);

        $this->searchEngine->getItem('newType', 'itemId');
    }

    public function testAddItemApiCall()
    {
        $item = array(
            "id"=> "id1",
            "title"=> "my title",
            "categories"=> array("cat1", "cat2")
        );
        $this->client->managementApiCall(
            'POST',
            'testHashid/items/newType',
            null,
            json_encode($item)
        )->shouldBeCalledTimes(1)->willReturn(array('response'=>array('id'=>'id1')));

        $this->assertEquals('id1', $this->searchEngine->addItem('newType', $item));

    }

    public function testAddItemsApiCall()
    {
        $items = array(
            array(
                "id"=> "id1",
                "title"=> "my title",
                "categories"=> array("cat1", "cat2")
            ),
            array(
                "id"=> "id2",
                "title"=> "my title2",
                "categories"=> array("cat1", "cat2")
            )
        );
        $this->client->managementApiCall(
            'POST',
            'testHashid/items/newType',
            null,
            json_encode($items)
        )->shouldBeCalledTimes(1)->willReturn(
            array(
                'response'=>array(
                    array('id'=>'id1'), array('id'=>'id2')
                )
            )
        );

        $result = $this->searchEngine->addItems('newType', $items);
        // returns ids inserted
        $this->assertEquals(array('id1', 'id2'), $result);
    }

    public function testUpdateItemApiCall()
    {
        $item = array("id"=>"id1", "title"=>"title1", "cats"=>array("cat1", "cat2"));
        $this->client->managementApiCall(
            'PUT',
            'testHashid/items/newType/idx',
            null,
            json_encode($item)
        )->shouldBeCalledTimes(1)->willReturn(
            array('statusCode' => 200));

        $this->assertTrue($this->searchEngine->updateItem('newType', 'idx', $item));
    }

    public function testUpdateItemsApiCall()
    {
        $items = array(
            array('id'=>'id1', 't'=>'t1', 'cats'=>array('cat1', 'cat2')),
            array('id'=>'id2', 't'=>'t2', 'cats'=>array('cat1', 'cat2'))
        );
        $this->client->managementApiCall(
            'PUT', 'testHashid/items/newType', null, json_encode($items)
        )->willReturn(array('statusCode'=>200));

        $this->assertTrue($this->searchEngine->updateItems('newType', $items));
    }

    public function testDeleteItemApiCall()
    {
        $this->client->managementApiCall('DELETE', 'testHashid/items/newType/id1')
                     -> willReturn(array('statusCode'=>204));
        $this->assertTrue($this->searchEngine->deleteItem('newType', 'id1'));
    }

    /**
     * @param \DateTime $from_date
     * @param \DateTime $to_date
     *
     * @dataProvider providerTestStatsReturnsAggregatesIteratorWithDifferentDates
     */
    public function testStatsReturnsAggregatesIteratorWithDifferentDates($from_date, $to_date)
    {
        if(is_null($from_date)){
            $aggregatesIterator = $this->searchEngine->stats();
        } else {
            $aggregatesIterator = $this->searchEngine->stats($from_date, $to_date);
        }
        // returns a aggregatesIterator
        $this->assertInstanceOf(
            '\Doofinder\Api\Management\AggregatesIterator', $aggregatesIterator
        );
        // with the right searchEngine
        $this->assertEquals(
            $this->searchEngine, $this->exposeAttribute($aggregatesIterator, 'searchEngine')
        );
        // and proper dates
        if(is_null($from_date)){
            $expected = array();
        } else {
            $expected = array('from'=>$from_date->format("Ymd"), 'to'=>$to_date->format("Ymd"));
        }
        $this->assertEquals(
            $expected, $this->exposeAttribute($aggregatesIterator, 'searchParams')
        );
    }

    public function providerTestStatsReturnsAggregatesIteratorWithDifferentDates()
    {
        return array(
            array(null, null), //default
            array(new \DateTime("20011-01-07"), new \DateTime("2011-02-07"))
        );
    }



    /**
     * Expose protected/private attribute of an object.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $attributeName Attribute to expose
     *
     * @return mixed Attribute Value.
     */
    public function exposeAttribute(&$object, $attributeName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $attribute = $reflection->getProperty($attributeName);
        $attribute->setAccessible(true);
        return $attribute->getValue($object);
    }

}