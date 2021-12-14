<?php

namespace Doofinder\Shared\Interfaces;

interface HttpClientInterface
{
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';

    /**
     * @param string $url
     * @param string $method
     * @param array $params
     * @param array $headers
     * @return HttpResponseInterface
     */
    public function request($url, $method, $params, $headers);
}