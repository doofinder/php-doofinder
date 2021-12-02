<?php

namespace Doofinder\Management\Resources;

use Doofinder\Configuration;
use Doofinder\Shared\Exceptions\RequestException;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Shared\Resource;

class SearchEngines extends Resource
{
    public static function create(HttpClientInterface $httpClient, Configuration $config)
    {
        return new self($httpClient, $config);
    }

    public function createSearchEngine(array $params)
    {
        try {
            $response = $this->httpClient->request(
                $this->baseUrl . '/api/v2/search_engines',
                'POST',
                $params,
                []
            );
            // Do mapping to return values
        } catch (RequestException $e) {
            //Do something and return error
        }
    }
}