<?php

namespace Doofinder\Management\Resources;

use Doofinder\Configuration;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Shared\Resource;

class Index extends Resource
{
    public static function create(HttpClientInterface $httpClient, Configuration $config)
    {
        return new self($httpClient, $config);
    }
}