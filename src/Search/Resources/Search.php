<?php

namespace Doofinder\Search\Resources;

use Doofinder\Configuration;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Shared\Interfaces\HttpResponseInterface;
use Doofinder\Shared\Resource;


/**
 * Search class is responsible for making the requests to the search's endpoint and return a response
 */
class Search extends SearchResource
{
    /**
     * @param HttpClientInterface $httpClient
     * @param Configuration $config
     * @return Search
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
        return $this->baseUrl . '/' . $hashId;
    }

    /**
     * Given a hashId and search params, makes a search
     *
     * @param string $hashId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function search($hashId, array $params)
    {
        return $this->requestWithToken(
            $this->getBaseUrl($hashId) . '/_search',
            HttpClientInterface::METHOD_GET,
            null,
            $params
        );
    }

    /**
     * Given a hashId and suggest params, it returns a suggest list
     *
     * @param string $hashId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function suggest($hashId, array $params)
    {
        return $this->requestWithToken(
            $this->getBaseUrl($hashId) . '/_suggest',
            HttpClientInterface::METHOD_GET,
            null,
            $params
        );
    }
}