<?php

namespace Tests\Unit\Management;

use Doofinder\Management\ManagementClient;
use Doofinder\Management\Resources\Index;
use Doofinder\Management\Resources\Item;
use Doofinder\Management\Resources\SearchEngine;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpStatusCode;
use PHPUnit_Framework_MockObject_MockObject;

abstract class BaseManagementClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $searchEngineResource;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $itemResource;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $indexResource;

    /**
     * @var ApiException
     */
    protected $unauthorizedException;

    /**
     * @var ApiException
     */
    protected $badParametersException;

    /**
     * @var ApiException
     */
    protected $notFoundException;

    protected function setUp()
    {
        $this->unauthorizedException = new ApiException('', HttpStatusCode::UNAUTHORIZED);
        $this->badParametersException = new ApiException(
            '{"error": {"code" : "bad_params", "message" : "Bad parameters error"}}',
            HttpStatusCode::BAD_REQUEST
        );
        $this->notFoundException = new ApiException(
            '{"error": {"code" : "not_found"}}',
            HttpStatusCode::NOT_FOUND
        );
        $this->searchEngineResource = $this->createMock(SearchEngine::class);
        $this->itemResource = $this->createMock(Item::class);
        $this->indexResource = $this->createMock(Index::class);
    }

    /**
     * @return ManagementClient
     */
    public function createSut()
    {
        return new ManagementClient(
            $this->searchEngineResource,
            $this->itemResource,
            $this->indexResource
        );
    }
}