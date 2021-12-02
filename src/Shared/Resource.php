<?php

namespace Doofinder\Shared;

use Doofinder\Configuration;
use Doofinder\Shared\Interfaces\HttpClientInterface;

abstract class Resource
{
    protected $httpClient;
    protected $config;
    protected $baseUrl;

    protected function __construct(HttpClientInterface $httpClient, Configuration $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
        $this->baseUrl = $config->getBaseUrl();
    }

    public function getConfig()
    {
        return $this->config;
    }
}