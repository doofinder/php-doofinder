<?php

namespace Doofinder\Management;

use Doofinder\Configuration;
use Doofinder\Management\Resources\Index;
use Doofinder\Management\Resources\Item;
use Doofinder\Management\Resources\SearchEngine;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpClient;
use Doofinder\Shared\Interfaces\HttpResponseInterface;
use Doofinder\Shared\Utils\ErrorHandler;

/**
 * This class is used to do management actions against search engines, index and items through calling an API.
 */
class ManagementClient
{
    /**
     * @var SearchEngine
     */
    private $searchEnginesResource;

    /**
     * @var Item
     */
    private $itemsResource;

    /**
     * @var Index
     */
    private $indexResource;

    public function __construct(
        SearchEngine $searchEnginesResource,
        Item $itemsResource,
        Index $indexesResource
    ) {
        $this->searchEnginesResource = $searchEnginesResource;
        $this->itemsResource = $itemsResource;
        $this->indexResource = $indexesResource;
    }

    /**
     * @param string $host
     * @param string $token
     * @param string $userId
     * @return ManagementClient
     */
    public static function create($host, $token, $userId)
    {
        $config = Configuration::create($host, $token, $userId);
        $httpClient = new HttpClient();

        return new self(
            SearchEngine::create($httpClient, $config),
            Item::create($httpClient, $config),
            Index::create($httpClient, $config)
        );
    }

    /**
     * Creates a new search engine with the provided data. It is not possible to run searches against the new search
     * engine as it does not have any index yet. You must create an index belonging to the new search engine to be able
     * to make searches.
     * @example $params = [
     *  'currency' => string,
     *  'language' => string,
     *  'name' => string,
     *  'site_url' => string|null,
     *  'stopwords' => boolean Default: false,
     *  'platform' => string,
     *  'has_grouping' => boolean,
     * ]
     *
     * @param array<string, mixed> $params = [
     *  'currency' => string, // Default: "EUR" ("AED", "ARS", "AUD", "BAM", "BDT", "BGN", "BOB", "BRL", "BYN", "CAD", "CHF", "CLP", "CNY", "COP", "CZK", "DKK", "DOP", "EGP", "EUR", "GBP", "HKD", "HRK", "HUF", "IDR", "ILS", "INR", "IRR", "ISK", "JPY", "KRW", "KWD", "MXN", "MYR", "NOK", "NZD", "PEN", "PLN", "RON", "RSD", "RUB", "SAR", "SEK", "TRY", "TWD", "UAH", "USD", "VEF", "VND", "XPF", "ZAR")
     *  'language' => string, // ("ar", "hy", "eu", "pt-br", "bg", "ca", "cs", "da", "nl", "en", "fi", "fr", "de", "el", "hi", "hu", "id", "it", "no", "pt", "ro", "ru", "es", "sv", "tr"),
     *  'name' => string,
     *  'site_url' => string|null,
     *  'stopwords' => boolean Default: false,
     *  'platform' => string, // Default: ("api", "api", "shopify", "woocommerce", "bigcommerce", "crawler", "ecommerce", "ekm", "file", "magento", "magento2", "opencart", "oscommerce", "prestashop", "shopify"),
     *  'has_grouping' => boolean, // Default: false
     * ]
     * @return HttpResponseInterface
     * @throws ApiException
     *
     */
    public function createSearchEngine(array $params)
    {
        try {
            return $this->searchEnginesResource->createSearchEngine($params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId and data updates a search engine.
     *
     * @param string $hashId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function updateSearchEngine($hashId, array $params)
    {
        try {
            return $this->searchEnginesResource->updateSearchEngine($hashId, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId gets a search engine details.
     *
     * @param string $hashId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function getSearchEngine($hashId)
    {
        try {
            return $this->searchEnginesResource->getSearchEngine($hashId);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Lists all user's search engines.
     *
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function listSearchEngines()
    {
        try {
            return $this->searchEnginesResource->listSearchEngines();
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId deletes a search engine.
     *
     * @param string $hashId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function deleteSearchEngine($hashId)
    {
        try {
            return $this->searchEnginesResource->deleteSearchEngine($hashId);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId and index data, creates a new index
     *
     * @param string $hashId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function createIndex($hashId, $params)
    {
        try {
            return $this->indexResource->createIndex($hashId, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, indexName and data updates an index.
     *
     * @param string $hashId
     * @param string $indexName
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function updateIndex($hashId, $indexName, $params)
    {
        try {
            return $this->indexResource->updateIndex($hashId, $indexName, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId and indexName, it gets an index.
     *
     * @param string $hashId
     * @param string $indexName
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function getIndex($hashId, $indexName)
    {
        try {
            return $this->indexResource->getIndex($hashId, $indexName);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, list index's search engine.
     *
     * @param string $hashId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function listIndexes($hashId)
    {
        try {
            return $this->indexResource->listIndexes($hashId);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId and indexName, removes an index
     *
     * @param string $hashId
     * @param string $indexName
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function deleteIndex($hashId, $indexName)
    {
        try {
            return $this->indexResource->deleteIndex($hashId, $indexName);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, index id and item data, creates a new item
     *
     * @param string $hashId
     * @param string $itemId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function createItem($hashId, $itemId, $params)
    {
        try {
            return $this->itemsResource->createItem($hashId, $itemId, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, indexName, item id and data updates an item.
     *
     * @param string $hashId
     * @param string $indexName
     * @param string $itemId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function updateItem($hashId, $indexName, $itemId, $params)
    {
        try {
            return $this->itemsResource->updateItem($hashId, $indexName, $itemId, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, indexName and item id, it gets an item.
     *
     * @param string $hashId
     * @param string $indexName
     * @param string $itemId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function getItem($hashId, $indexName, $itemId)
    {
        try {
            return $this->itemsResource->getItem($hashId, $indexName, $itemId);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId and index name, scrolls index
     *
     * @param string $hashId
     * @param string $indexName
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function scrollIndex($hashId, $indexName, $params = [])
    {
        try {
            return $this->itemsResource->scrollIndex($hashId, $indexName, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, indexName and item id, removes an item
     *
     * @param string $hashId
     * @param string $indexName
     * @param string $itemId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function deleteItem($hashId, $indexName, $itemId)
    {
        try {
            return $this->itemsResource->deleteItem($hashId, $indexName, $itemId);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
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
        try {
            return $this->indexResource->createTemporaryIndex($hashId, $indexName);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
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
        try {
            return $this->indexResource->deleteTemporaryIndex($hashId, $indexName);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
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
        try {
            return $this->indexResource->replaceIndex($hashId, $indexName);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId and index name, reindex all items from real and index them onto the temporary.
     *
     * @param string $hashId
     * @param string $indexName
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function reindexIntoTemporary($hashId, $indexName)
    {
        try {
            return $this->indexResource->reindexIntoTemporary($hashId, $indexName);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId and index name, returns the status of the last scheduled reindexing tasks.
     *
     * @param string $hashId
     * @param string $indexName
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function reindexTaskStatus($hashId, $indexName)
    {
        try {
            return $this->indexResource->reindexTaskStatus($hashId, $indexName);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId schedules a task for processing all search engine's data sources.
     *
     * @param string $hashId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function processSearchEngine($hashId, array $params = [])
    {
        try {
            return $this->searchEnginesResource->processSearchEngine($hashId, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId gets the status of the last process task.
     *
     * @param string $hashId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function getSearchEngineProcessStatus($hashId)
    {
        try {
            return $this->searchEnginesResource->getSearchEngineProcessStatus($hashId);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, index id and item data, creates a new item in temporal index
     *
     * @param string $hashId
     * @param string $indexName
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function createItemInTemporalIndex($hashId, $indexName, $params)
    {
        try {
            return $this->itemsResource->createItemInTemporalIndex($hashId, $indexName, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, indexName, item id and data updates an item on temporal index.
     *
     * @param string $hashId
     * @param string $indexName
     * @param string $itemId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function updateItemInTemporalIndex($hashId, $indexName, $itemId, $params)
    {
        try {
            return $this->itemsResource->updateItemInTemporalIndex($hashId, $indexName, $itemId, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, indexName and item id, it gets an item from temporal index.
     *
     * @param string $hashId
     * @param string $indexName
     * @param string $itemId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function getItemFromTemporalIndex($hashId, $indexName, $itemId)
    {
        try {
            return $this->itemsResource->getItemFromTemporalIndex($hashId, $indexName, $itemId);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, indexName and item id, removes an item from temporal index
     *
     * @param string $hashId
     * @param string $indexName
     * @param string $itemId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function deleteItemFromTemporalIndex($hashId, $indexName, $itemId)
    {
        try {
            return $this->itemsResource->deleteItemFromTemporalIndex($hashId, $indexName, $itemId);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, indexName and params, it gets an item list from temporal index.
     *
     * @param string $hashId
     * @param string $indexName
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function findItemsFromTemporalIndex($hashId, $indexName, $params)
    {
        try {
            return $this->itemsResource->findItemsFromTemporalIndex($hashId, $indexName, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, indexName and params, it gets an item list.
     *
     * @param string $hashId
     * @param string $indexName
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function findItems($hashId, $indexName, $params)
    {
        try {
            return $this->itemsResource->findItems($hashId, $indexName, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId and indexName, returns the total number of items in the index.
     *
     * @param string $hashId
     * @param string $indexName
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function countItems($hashId, $indexName)
    {
        try {
            return $this->itemsResource->countItems($hashId, $indexName);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, index id and items data, creates new items in temporal index
     *
     * @param string $hashId
     * @param string $indexName
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function createItemsInBulkInTemporalIndex($hashId, $indexName, $params)
    {
        try {
            return $this->itemsResource->createItemsInBulkInTemporalIndex($hashId, $indexName, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, index id and items data, updates items in temporal index
     *
     * @param string $hashId
     * @param string $indexName
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function updateItemsInBulkInTemporalIndex($hashId, $indexName, $params)
    {
        try {
            return $this->itemsResource->updateItemsInBulkInTemporalIndex($hashId, $indexName, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, index id and items id, deletes items in temporal index
     *
     * @param string $hashId
     * @param string $indexName
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function deleteItemsInBulkInTemporalIndex($hashId, $indexName, $params)
    {
        try {
            return $this->itemsResource->deleteItemsInBulkInTemporalIndex($hashId, $indexName, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, index id and items data, creates new items
     *
     * @param string $hashId
     * @param string $indexName
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function createItemsInBulk($hashId, $indexName, $params)
    {
        try {
            return $this->itemsResource->createItemsInBulk($hashId, $indexName, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, index id and items data, updates items
     *
     * @param string $hashId
     * @param string $indexName
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function updateItemsInBulk($hashId, $indexName, $params)
    {
        try {
            return $this->itemsResource->updateItemsInBulk($hashId, $indexName, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }

    /**
     * Given a hashId, index id and items id, deletes items
     *
     * @param string $hashId
     * @param string $indexName
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function deleteItemsInBulk($hashId, $indexName, $params)
    {
        try {
            return $this->itemsResource->deleteItemsInBulk($hashId, $indexName, $params);
        } catch (ApiException $e) {
            throw ErrorHandler::create($e->getCode(), $e->getMessage(), $e);
        }
    }
}