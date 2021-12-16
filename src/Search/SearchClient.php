<?php

namespace Doofinder\Search;

use Doofinder\Configuration;
use Doofinder\Search\Resources\Search;
use Doofinder\Shared\HttpClient;

class SearchClient
{
    private $searchesResource;

    private function __construct($host, $token, $userId)
    {
        $config = Configuration::create($host, $token, $userId);
        $httpClient = new HttpClient();
        $this->searchesResource = Search::create($httpClient, $config);
    }

    public static function create($host, $token, $userId)
    {
        return new self($host, $token, $userId);
    }
}