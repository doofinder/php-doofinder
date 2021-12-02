<?php

namespace Tests\Unit\Management;

use Doofinder\Management\ManagementClient;

class ManagementClientTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function createSut()
    {
        return ManagementClient::create('fake_host', 'fake_token');
    }

    public function testGetProcessStatus()
    {
        $managementClient = $this->createSut();
        $result = $managementClient->getProcessStatus('fake_hash_id');
        $this->assertTrue(is_array($result));
        $this->assertEmpty($result);
    }

    public function testCreateSearchEngine()
    {
        $managementClient = $this->createSut();
        $params = [];
        $this->expectException(\Exception::class);
        $managementClient->createSearchEngine($params);
    }
}