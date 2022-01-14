<?php

namespace Doofinder\Search\Resources;

use Doofinder\Configuration;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Shared\Interfaces\HttpResponseInterface;
use Doofinder\Shared\Resource;


/**
 * Stat class is responsible for making the requests to the stat's endpoint and return a response
 */
class Stat extends SearchResource
{
    /**
     * @param HttpClientInterface $httpClient
     * @param Configuration $config
     * @return Stat
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
        return $this->baseUrl . '/' . $hashId . '/stats';
    }

    /**
     * Given a hashId and search params, starts a session
     *
     * @param string $hashId
     * @param string $sessionId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function initSession($hashId, $sessionId)
    {
        return $this->requestWithToken(
            $this->getBaseUrl($hashId) . '/init',
            HttpClientInterface::METHOD_PUT,
            null,
            ['session_id' => $sessionId]
        );
    }

    /**
     * Given a hashId and redirection params, log a redirection
     *
     * @param string $hashId
     * @param string $sessionId
     * @param string $id
     * @param string|null $query
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function logRedirection($hashId, $sessionId, $id, $query)
    {
        $params = [
            'session_id' => $sessionId,
            'id' => $id
        ];

        if (!is_null($query)) {
            $params['query'] = $query;
        }

        return $this->requestWithToken(
            $this->getBaseUrl($hashId) . '/redirect',
            HttpClientInterface::METHOD_PUT,
            null,
            $params
        );
    }
}