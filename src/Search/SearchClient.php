<?php

namespace Doofinder\Search;

use Doofinder\Configuration;
use Doofinder\Search\Resources\Search;
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

    public function __construct(Search $searchResource)
    {
        $this->searchResource = $searchResource;
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

        return new self($searchResource);
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
}