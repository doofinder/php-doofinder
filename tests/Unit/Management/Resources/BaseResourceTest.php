<?php

namespace Tests\Unit\Management\Resources;

use Doofinder\Configuration;
use Doofinder\Shared\HttpClient;

class BaseResourceTest extends \PHPUnit_Framework_TestCase
{
    const BASE_URL = 'https://fake_url.com/random/api/v2';
    const TOKEN = 'fake_token';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpClient;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $config;

    public function setUp()
    {
        $this->httpClient = $this->createMock(HttpClient::class);
        $this->config = $this->createMock(Configuration::class);

    }

    /**
     * @return \PHPUnit_Framework_Constraint_Callback
     */
    protected function assertBearerCallback()
    {
        return $this->callback(
            function ($authentication) {
                $this->assertStringStartsWith('Authorization: Bearer ', $authentication[0]);
                return true;
            }
        );
    }

    protected function setConfig()
    {
        $this->config
            ->expects($this->once())
            ->method('getBaseUrl')
            ->willReturn(self::BASE_URL);

        $this->config
            ->expects($this->once())
            ->method('getToken')
            ->willReturn(self::TOKEN);
    }
}