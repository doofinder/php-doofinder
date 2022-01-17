<?php

namespace Doofinder\Search;

use Doofinder\Configuration;
use Doofinder\Search\Resources\Search;
use Doofinder\Search\Resources\Stat;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpClient;
use Doofinder\Shared\Utils\ErrorHandler;
use Doofinder\Shared\Interfaces\HttpResponseInterface;

/**
 * This class is used to do searches through calling an API.
 */
class SearchClient
{
    /**
     * @var Search
     */
    private $searchResource;

    /**
     * @var Stat
     */
    private $statResource;

    public function __construct(Search $searchResource, Stat $statResource)
    {
        $this->searchResource = $searchResource;
        $this->statResource = $statResource;
    }

    /**
     * @param string $host
     * @param string $token
     * @return SearchClient
     */
    public static function create($host, $token)
    {
        $config = Configuration::create($host, $token);
        $httpClient = new HttpClient();
        $searchResource = Search::create($httpClient, $config);
        $statResource = Stat::create($httpClient, $config);

        return new self($searchResource, $statResource);
    }

    /**
     * Search through indexed items of a search engine.
     *
     * @param string $hashId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function search($hashId, array $params)
    {
        try {
            return $this->searchResource->search($hashId, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Search through indexed suggestions of a search engine.
     *
     * @param string $hashId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function suggest($hashId, array $params)
    {
        try {
            return $this->searchResource->suggest($hashId, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Starts a session identified by a session_id. The session is used to "group" events.
     *
     * @param string $hashId
     * @param string $sessionId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function initSession($hashId, $sessionId)
    {
        try {
            return $this->statResource->initSession($hashId, $sessionId);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }


    /**
     * Logs a "redirection triggered" event in stats logs.
     *
     * @param string $hashId
     * @param string $sessionId
     * @param string $id
     * @param string|null $query
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function logRedirection($hashId, $sessionId, $id, $query = null)
    {
        try {
            return $this->statResource->logRedirection($hashId, $sessionId, $id, $query);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }
}