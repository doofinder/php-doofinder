<?php

namespace Doofinder\Management\Resources;

use Doofinder\Configuration;
use Doofinder\Shared\Interfaces\HttpClientInterface;

class SearchEngines
{
    private $httpClient;
    private $config;

    private function __construct(HttpClientInterface $httpClient, Configuration $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    public static function create(HttpClientInterface $httpClient, Configuration $config)
    {
        return new self($httpClient, $config);
    }
}