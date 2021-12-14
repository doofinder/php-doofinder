<?php

namespace Doofinder\Shared\Interfaces;

interface HttpResponseInterface
{
    public static function create($statusCode, $body = null);
    public function getBody();
    public function getStatusCode();
    public function setBody($body);
}