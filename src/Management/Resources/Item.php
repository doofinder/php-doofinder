<?php

namespace Doofinder\Management\Resources;

use Doofinder\Configuration;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Shared\Interfaces\HttpResponseInterface;
use Doofinder\Management\Model\Item as ItemModel;

/**
 * Item class is responsible for making the requests to the item's endpoints and return a response
 */
class Item extends ManagementResource
{
    /**
     * @param HttpClientInterface $httpClient
     * @param Configuration $config
     * @return Item
     */
    public static function create(HttpClientInterface $httpClient, Configuration $config)
    {
        return new self($httpClient, $config);
    }

    /**
     * @param string $hashId
     * @param string $indexName
     * @param string|null $itemId
     * @param bool $isTemporalIndex
     * @return string
     */
    private function getBaseUrl($hashId, $indexName, $itemId = null, $isTemporalIndex = false)
    {
        $temporalIndex = $isTemporalIndex ? '/temp' : '';

        return $this->baseUrl . '/search_engines/' . $hashId . '/indices/' . $indexName . $temporalIndex . '/items' .(!is_null($itemId)? '/' . $itemId : '');
    }

    /**
     * Given a hashId, index name and item data, creates a new item
     *
     * @param string $hashId
     * @param string $indexName
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function createItem($hashId, $indexName, array $params)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexName),
            HttpClientInterface::METHOD_POST,
            ItemModel::class,
            $params
        );
    }

    /**
     * Given a hashId, indexName, item id and data, updates an item
     *
     * @param string $hashId
     * @param string $indexName
     * @param string $itemId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function updateItem($hashId, $indexName, $itemId, array $params)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexName, $itemId),
            HttpClientInterface::METHOD_PATCH,
            ItemModel::class,
            $params
        );
    }

    /**
     * Given a hashId, indexName, item id and data, it gets an item
     *
     * @param string $hashId
     * @param string $indexName
     * @param string $itemId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function getItem($hashId, $indexName, $itemId)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexName, $itemId),
            HttpClientInterface::METHOD_GET,
            ItemModel::class
        );
    }

    /**
     * Given a hashId and index name, scrolls index
     *
     * @param string $hashId
     * @param string $indexName
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function scrollIndex($hashId, $indexName, $params)
    {
        $httpResponse = $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexName),
            HttpClientInterface::METHOD_GET,
            null,
            $params
        );

        $body = $httpResponse->getBody();

        $body['items'] = array_map(function (array $item) {
            return ItemModel::createFromArray($item);
        }, $body['items']);

        $httpResponse->setBody(
            $body
        );
        return $httpResponse;
    }

    /**
     * Given a hashId, indexName and item id, deletes an item
     *
     * @param string $hashId
     * @param string $indexName
     * @param string $itemId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function deleteItem($hashId, $indexName, $itemId)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexName, $itemId),
            HttpClientInterface::METHOD_DELETE
        );
    }

    /**
     * Given a hashId, index name and item data, creates a new item in temporal index
     *
     * @param string $hashId
     * @param string $indexName
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function createItemInTemporalIndex($hashId, $indexName, array $params)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexName, null, true),
            HttpClientInterface::METHOD_POST,
            ItemModel::class,
            $params
        );
    }

    /**
     * Given a hashId, indexName, item id and data, updates an item in temporal index
     *
     * @param string $hashId
     * @param string $indexName
     * @param string $itemId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function updateItemInTemporalIndex($hashId, $indexName, $itemId, array $params)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId, $indexName, $itemId, true),
            HttpClientInterface::METHOD_PATCH,
            ItemModel::class,
            $params
        );
    }
}