<?php

namespace Doofinder\Management\Resources;

use Doofinder\Configuration;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Shared\Interfaces\HttpResponseInterface;
use Doofinder\Management\Model\SearchEngine as SearchEngineModel;

/**
 * SearchEngines class is responsible for making the requests to the search_engine's endpoints and return a response
 */
class SearchEngine extends ManagementResource
{
    /**
     * @param HttpClientInterface $httpClient
     * @param Configuration $config
     * @return SearchEngine
     */
    public static function create(HttpClientInterface $httpClient, Configuration $config)
    {
        return new self($httpClient, $config);
    }

    /**
     * @param string|null $hashId
     * @return string
     */
    private function getBaseUrl($hashId = null)
    {
        return $this->baseUrl . '/search_engines' .(!is_null($hashId)? '/' . $hashId : '');
    }

    /**
     * Creates a new search engine
     *
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function createSearchEngine(array $params)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl(),
            HttpClientInterface::METHOD_POST,
            SearchEngineModel::class,
            $params
        );
    }

    /**
     * Given a hashId and data updates a search engine
     *
     * @param string $hashId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function updateSearchEngine($hashId, array $params)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId),
            HttpClientInterface::METHOD_PATCH,
            SearchEngineModel::class,
            $params
        );
    }

    /**
     * Given a hashId gets a search engine
     *
     * @param string $hashId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function getSearchEngine($hashId)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId),
            HttpClientInterface::METHOD_GET,
            SearchEngineModel::class
        );
    }

    /**
     * List a user's search engines
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function listSearchEngines()
    {
        $httpResponse = $this->requestWithJwt(
            $this->getBaseUrl(),
            HttpClientInterface::METHOD_GET
        );

        $httpResponse->setBody(
            array_map(function (array $searchEngine) {
                return SearchEngineModel::createFromArray($searchEngine);
            },
            $httpResponse->getBody()
        ));

        return $httpResponse;
    }

    /**
     * Given a hashId deletes a search engine
     *
     * @param string $hashId
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function deleteSearchEngine($hashId)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId),
            HttpClientInterface::METHOD_DELETE
        );
    }

    /**
     * Given a hashId schedules a task for processing all search engine's data sources.
     *
     * @param string $hashId
     * @param array $params
     * @return HttpResponseInterface
     * @throws ApiException
     */
    public function processSearchEngine($hashId, array $params)
    {
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId) . '/_process',
            HttpClientInterface::METHOD_POST,
            null,
            $params
        );
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
        return $this->requestWithJwt(
            $this->getBaseUrl($hashId) . '/_process',
            HttpClientInterface::METHOD_GET
        );
    }
}