<?php

namespace Doofinder\Search;

use Doofinder\Configuration;
use Doofinder\Search\Resources\Searches;
use Doofinder\Shared\HttpClient;

class SearchClient
{
    private $searchesResource;

    private function __construct($host, $token)
    {
        $config = Configuration::create($host, $token);
        $httpClient = new HttpClient();
        $this->searchesResource = Searches::create($httpClient, $config);
    }

    public static function create($host, $token)
    {
        return new self($host, $token);
    }
}