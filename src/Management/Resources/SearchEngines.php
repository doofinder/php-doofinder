<?php

namespace Doofinder\Management\Resources;

use Doofinder\Configuration;
use Doofinder\Management\Model\SearchEngine;
use Doofinder\Management\Model\SearchEngineList;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Shared\Interfaces\HttpResponseInterface;
use Doofinder\Shared\Resource;

class SearchEngines extends Resource
{
    /**
     * @param HttpClientInterface $httpClient
     * @param Configuration $config
     * @return SearchEngines
     */
    public static function create(HttpClientInterface $httpClient, Configuration $config)
    {
        return new self($httpClient, $config);
    }

    /**
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function createSearchEngine(array $params)
    {
        return $this->requestWithJwt(
            $this->baseUrl . '/search_engines',
            HttpClientInterface::METHOD_POST,
            SearchEngine::class,
            $params
        );
    }

    /**
     * @param string $hashId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function updateSearchEngine($hashId, array $params)
    {
        return $this->requestWithJwt(
            $this->baseUrl . '/search_engines/' . $hashId,
            HttpClientInterface::METHOD_PATCH,
            SearchEngine::class,
            $params
        );
    }

    /**
     * @param string $hashId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function getSearchEngine($hashId)
    {
        return $this->requestWithJwt(
            $this->baseUrl . '/search_engines/' . $hashId,
            HttpClientInterface::METHOD_GET,
            SearchEngine::class
        );
    }

    /**
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function listSearchEngines()
    {
        return $this->requestWithJwt(
            $this->baseUrl . '/search_engines',
            HttpClientInterface::METHOD_GET,
            SearchEngineList::class
        );
    }

    /**
     * @param string $hashId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function deleteSearchEngine($hashId)
    {
        return $this->requestWithJwt(
            $this->baseUrl . '/search_engines/' . $hashId,
            HttpClientInterface::METHOD_DELETE
        );
    }
}