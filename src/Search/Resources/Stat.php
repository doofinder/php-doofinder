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

    /**
     * Given a hashId and banner params, log a banner click
     *
     * @param string $hashId
     * @param string $sessionId
     * @param string $id
     * @param string|null $query
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function logBanner($hashId, $sessionId, $id, $query)
    {
        $params = [
            'session_id' => $sessionId,
            'id' => $id
        ];

        if (!is_null($query)) {
            $params['query'] = $query;
        }

        return $this->requestWithToken(
            $this->getBaseUrl($hashId) . '/image',
            HttpClientInterface::METHOD_PUT,
            null,
            $params
        );
    }

    /**
     * Given a hashId and sessionId, logs a checkout
     *
     * @param string $hashId
     * @param string $sessionId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function logCheckout($hashId, $sessionId)
    {
        return $this->requestWithToken(
            $this->getBaseUrl($hashId) . '/checkout',
            HttpClientInterface::METHOD_PUT,
            null,
            ['session_id' => $sessionId]
        );
    }

    /**
     * Given a hashId, session id, item id and click params, log a click
     *
     * @param string $hashId
     * @param string $sessionId
     * @param string $itemId
     * @param string|null $query
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function logClick($hashId, $sessionId, $itemId, $query)
    {
        $params = [
            'session_id' => $sessionId,
            'dfid' => $itemId
        ];

        if (!is_null($query)) {
            $params['query'] = $query;
        }

        return $this->requestWithToken(
            $this->getBaseUrl($hashId) . '/click',
            HttpClientInterface::METHOD_PUT,
            null,
            $params
        );
    }

    /**
     * Log an "Add to cart" event
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
        return $this->requestWithToken(
            $this->getBaseUrl($hashId) . '/cart/' . $sessionId,
            HttpClientInterface::METHOD_PUT,
            null,
            [
                'amount' => $amount,
                'id' => $itemId,
                'index' => $indexId,
                'price' => $price,
                'title' => $title
            ]
        );
    }

    /**
     * Log "Remove from cart" event
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
        return $this->requestWithToken(
            $this->getBaseUrl($hashId) . '/cart/' . $sessionId,
            HttpClientInterface::METHOD_PATCH,
            null,
            [
                'amount' => $amount,
                'id' => $itemId,
                'index' => $indexId
            ]
        );
    }
}