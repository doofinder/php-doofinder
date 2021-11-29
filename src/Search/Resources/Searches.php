<?php

namespace Doofinder\Search\Resources;

use Doofinder\Configuration;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Shared\Resource;

class Searches extends Resource
{
    public static function create(HttpClientInterface $httpClient, Configuration $config)
    {
        return new self($httpClient, $config);
    }
}