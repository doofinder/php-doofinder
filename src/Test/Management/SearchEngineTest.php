<?php

namespace Doofinder\Api\Test;

use Doofinder\Api\Management\SearchEngine;
use Doofinder\Api\Management\ScrollIterator;

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
        )->shouldBeCalledTimes(1);

        $this->searchEngine->addItem('newType', $item);

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
                    array('id'=>'i1'), array('id'=>'i2')
                )
            )
        );

        $this->searchEngine->addItems('newType', $items);
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