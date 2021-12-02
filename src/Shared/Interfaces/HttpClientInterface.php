<?php

namespace Doofinder\Shared\Interfaces;

interface HttpClientInterface
{
    public function request($url, $method, $params, $options);
}