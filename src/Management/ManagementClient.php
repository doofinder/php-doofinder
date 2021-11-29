<?php

namespace Doofinder\Management;

use Doofinder\Configuration;
use Doofinder\Management\Resources\Indexes;
use Doofinder\Management\Resources\Items;
use Doofinder\Management\Resources\SearchEngines;
use Doofinder\Shared\HttpClient;

class ManagementClient
{
    private $searchEnginesResource;
    private $itemsResource;
    private $indexesResource;

    private function __construct($host, $token)
    {
        $config = Configuration::create($host, $token);
        $httpClient = new HttpClient();
        $this->searchEnginesResource = SearchEngines::create($httpClient, $config);
        $this->itemsResource = Items::create($httpClient, $config);
        $this->indexesResource = Indexes::create($httpClient, $config);
    }

    public static function create($host, $token)
    {
        return new self($host, $token);
    }

    public function getProcessStatus(string $hashId)
    {
        // GET /api/v2/search_engines/{hashid}/_process
        return [];
    }

    public function processTask()
    {
        // POST /api/v2/search_engines/{hashid}/_process
    }

    public function getSearchEngine()
    {
        // GET /api/v2/search_engines/{hashid}
    }

    public function deleteSearchEngine()
    {
        // DELETE /api/v2/search_engines/{hashid}
    }

    public function updateSearchEngine()
    {
        // PATCH /api/v2/search_engines/{hashid}
    }

    public function listSearchEngines()
    {
        // GET /api/v2/search_engines
    }

    public function createSearchEngine()
    {
        // POST /api/v2/search_engines
    }



}