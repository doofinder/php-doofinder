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
     * @return string
     */
    private function getBaseUrl($hashId)
    {
        return $this->baseUrl . '/search_engines/' . $hashId . '/indices/';
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
}