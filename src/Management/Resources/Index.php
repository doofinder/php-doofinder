<?php

namespace Doofinder\Management\Resources;

use Doofinder\Configuration;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Shared\Interfaces\HttpResponseInterface;
use Doofinder\Shared\Resource;
use Doofinder\Management\Model\Index as IndexModel;

/**
 * Index class is responsible for making the requests to the index's endpoints and return a response
 */
class Index extends Resource
{
    /**
     * @param HttpClientInterface $httpClient
     * @param Configuration $config
     * @return Index
     */
    public static function create(HttpClientInterface $httpClient, Configuration $config)
    {
        return new self($httpClient, $config);
    }

    /**
     * @param string $hashId
     * @param string $indexId
     * @return string
     */
    private function getBaseUrl($hashId, $indexId = '')
    {
        return $this->baseUrl . '/search_engines/' . $hashId . '/indices/' . $indexId;
    }

    /**
     * Given a hashId and index data, creates a new index
     *
     * @param string $hashId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function createIndex($hashId, array $params)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId),
            HttpClientInterface::METHOD_POST,
            IndexModel::class,
            $params
        );
    }

    /**
     * Given a hashId, indexId and data updates an index
     *
     * @param string $hashId
     * @param string $indexId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function updateIndex($hashId, $indexId, array $params)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexId),
            HttpClientInterface::METHOD_PATCH,
            IndexModel::class,
            $params
        );
    }

    /**
     * Given a hashId, indexId and data, it gets an index
     *
     * @param string $hashId
     * @param string $indexId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function getIndex($hashId, $indexId)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexId),
            HttpClientInterface::METHOD_GET,
            IndexModel::class
        );
    }

    /**
     * Given a hashId, lists index's search engine
     *
     * @param string $hashId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function listIndexes($hashId)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId),
            HttpClientInterface::METHOD_GET,
            IndexModel::class
        );
    }
}