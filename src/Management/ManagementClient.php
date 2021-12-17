<?php

namespace Doofinder\Management;

use Doofinder\Configuration;
use Doofinder\Management\Resources\Index;
use Doofinder\Management\Resources\Item;
use Doofinder\Management\Resources\SearchEngine;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpClient;
use Doofinder\Shared\Interfaces\HttpResponseInterface;
use Doofinder\Shared\Utils\ErrorHandler;

/**
 * This class is used to do management actions against search engines, index and items through calling an API.
 */
class ManagementClient
{
    /**
     * @var SearchEngine
     */
    private $searchEnginesResource;

    /**
     * @var Item
     */
    private $itemsResource;

    /**
     * @var Index
     */
    private $indexesResource;

    public function __construct(
        $searchEnginesResource,
        $itemsResource,
        $indexesResource
    ) {
        $this->searchEnginesResource = $searchEnginesResource;
        $this->itemsResource = $itemsResource;
        $this->indexesResource = $indexesResource;
    }

    /**
     * @param string $host
     * @param string $token
     * @param string $userId
     * @return ManagementClient
     */
    public static function create($host, $token, $userId)
    {
        $config = Configuration::create($host, $token, $userId);
        $httpClient = new HttpClient();

        return new self(
            SearchEngine::create($httpClient, $config),
            Item::create($httpClient, $config),
            Index::create($httpClient, $config)
        );
    }

    /**
     * Creates a new search engine with the provided data. It is not possible to run searches against the new search
     * engine as it does not have any index yet. You must create an index belonging to the new search engine to be able
     * to make searches.
     *
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     *
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
     * Given a hashId and data updates a search engine.
     *
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
     * Given a hashId gets a search engine details.
     *
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
     * Lists all user's search engines.
     *
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
     * Given a hashId deletes a search engine.
     *
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

    /**
     * Given a hashId and index data, creates a new index
     *
     * @param string $hashId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function createIndex($hashId, $params)
    {
        try {
            return $this->indexesResource->createIndex($hashId, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }
}