<?php

namespace Doofinder\Shared\Interfaces;

interface HttpClientInterface
{
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const METHOD_PATCH = 'PATCH';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    public function request($url, $method, $params, $headers);
}