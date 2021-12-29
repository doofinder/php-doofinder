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
     * @param string|null $indexName
     * @return string
     */
    private function getBaseUrl($hashId, $indexName = null)
    {
        return $this->baseUrl . '/search_engines/' . $hashId . '/indices' . (!is_null($indexName)? '/' . $indexName : '');
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
     * Given a hashId, indexName and data updates an index
     *
     * @param string $hashId
     * @param string $indexName
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function updateIndex($hashId, $indexName, array $params)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexName),
            HttpClientInterface::METHOD_PATCH,
            IndexModel::class,
            $params
        );
    }

    /**
     * Given a hashId, indexName and data, it gets an index
     *
     * @param string $hashId
     * @param string $indexName
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function getIndex($hashId, $indexName)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexName),
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
        $httpResponse = $this->requestWithJwt(
            $this->getBaseUrl($hashId),
            HttpClientInterface::METHOD_GET
        );

        $httpResponse->setBody(
            array_map(function (array $index) {
                return IndexModel::createFromArray($index);
            }, $httpResponse->getBody())
        );
        return $httpResponse;
    }

    /**
     * Given a hashId and indexName, deletes an index
     *
     * @param string $hashId
     * @param string $indexName
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function deleteIndex($hashId, $indexName)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexName),
            HttpClientInterface::METHOD_DELETE
        );
    }

    /**
     * Given a hashId and index name, creates a new temporary index
     *
     * @param string $hashId
     * @param string $indexName
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function createTemporaryIndex($hashId, $indexName)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexName) . '/temp',
            HttpClientInterface::METHOD_POST
        );
    }

    /**
     * Given a hashId and index name, deletes a temporary index
     *
     * @param string $hashId
     * @param string $indexName
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function deleteTemporaryIndex($hashId, $indexName)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexName) . '/temp',
            HttpClientInterface::METHOD_DELETE
        );
    }

    /**
     * Given a hashId and index name, replaces the content of the current "production" index with the content of the temporary one
     *
     * @param string $hashId
     * @param string $indexName
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function replaceIndex($hashId, $indexName)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexName) . '/_replace_by_temp',
            HttpClientInterface::METHOD_POST
        );
    }
}