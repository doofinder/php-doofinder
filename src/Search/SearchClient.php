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

    /**
     * Logs a "click on banner image" event in stats logs.
     *
     * @param string $hashId
     * @param string $sessionId
     * @param string $id
     * @param string|null $query
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function logBanner($hashId, $sessionId, $id, $query = null)
    {
        try {
            return $this->statResource->logBanner($hashId, $sessionId, $id, $query);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Logs a checkout event in stats logs.
     *
     * @param string $hashId
     * @param string $sessionId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function logCheckout($hashId, $sessionId)
    {
        try {
            return $this->statResource->logCheckout($hashId, $sessionId);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }


    /**
     * Save click event on doofinder statistics
     *
     * @param string $hashId
     * @param string $sessionId
     * @param string $itemId
     * @param string|null $query
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function logClick($hashId, $sessionId, $itemId, $query = null)
    {
        try {
            return $this->statResource->logClick($hashId, $sessionId, $itemId, $query);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }


    /**
     * Adds an item to the cart, or creates a new cart for the given session if it does not exists
     *
     * @param string $hashId
     * @param string $sessionId
     * @param integer $amount
     * @param string $itemId
     * @param string $indexId
     * @param float $price
     * @param string $title
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function logAddToCart($hashId, $sessionId, $amount, $itemId, $indexId, $price, $title)
    {
        try {
            return $this->statResource->logAddToCart($hashId, $sessionId, $amount, $itemId, $indexId, $price, $title);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Removes an amount from the given item in the cart, removing it completely if the amount present in the cart minus
     * the amount specified in this call is zero or negative, else, it will be updated with the new calculated amount.
     *
     * @param string $hashId
     * @param string $sessionId
     * @param integer $amount
     * @param string $itemId
     * @param string $indexId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function logRemoveFromCart($hashId, $sessionId, $amount, $itemId, $indexId)
    {
        try {
            return $this->statResource->logRemoveFromCart($hashId, $sessionId, $amount, $itemId, $indexId);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * This call will erase completely a cart identified by the pair of hashid and session ID, if it does exist
     *
     * @param string $hashId
     * @param string $sessionId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function clearCart($hashId, $sessionId)
    {
        try {
            return $this->statResource->clearCart($hashId, $sessionId);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }
}