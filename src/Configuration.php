<?php

namespace Doofinder;

class Configuration
{
    private $host;
    private $token;

    private function __construct($host, $token)
    {
        $this->host = $host;
        $this->token = $token;
    }

    public static function create($host, $token)
    {
        return new self($host, $token);
    }
}