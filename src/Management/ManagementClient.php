<?php

namespace Doofinder\Management;

use Doofinder\Configuration;
use Doofinder\Management\Resources\Indexes;
use Doofinder\Management\Resources\Items;
use Doofinder\Management\Resources\SearchEngines;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpClient;
use Doofinder\Shared\Interfaces\HttpResponseInterface;
use Doofinder\Shared\Utils\ErrorHandler;

class ManagementClient
{
    /**
     * @var SearchEngines
     */
    private $searchEnginesResource;

    /**
     * @var Items
     */
    private $itemsResource;

    /**
     * @var Indexes
     */
    private $indexesResource;

    private function __construct(
        $searchEnginesResource,
        $itemsResource,
        $indexesResource
    ) {
        $this->searchEnginesResource = $searchEnginesResource;
        $this->itemsResource = $itemsResource;
        $this->indexesResource = $indexesResource;
    }

    public static function create($host, $token)
    {
        $config = Configuration::create($host, $token, 'Management');
        $httpClient = new HttpClient();

        return new self(
            SearchEngines::create($httpClient, $config),
            Items::create($httpClient, $config),
            Indexes::create($httpClient, $config)
        );
    }

    public static function createForTest($searchEnginesResource, $itemsResource, $indexesResource)
    {
        return new self($searchEnginesResource, $itemsResource, $indexesResource);
    }

    public function getProcessStatus($hashId)
    {

        // GET /api/v2/search_engines/{hashid}/_process
        return [];
    }

    public function processTask()
    {
        // POST /api/v2/search_engines/{hashid}/_process
    }

    /**
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function createSearchEngine(array $params)
    {
        try {
            return $this->searchEnginesResource->createSearchEngine($params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * @param string $hashId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function updateSearchEngine($hashId, array $params)
    {
        try {
            return $this->searchEnginesResource->updateSearchEngine($hashId, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * @param string $hashId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function getSearchEngine($hashId)
    {
        try {
            return $this->searchEnginesResource->getSearchEngine($hashId);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function listSearchEngines()
    {
        try {
            return $this->searchEnginesResource->listSearchEngines();
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * @param string $hashId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function deleteSearchEngine($hashId)
    {
        try {
            return $this->searchEnginesResource->deleteSearchEngine($hashId);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }
}