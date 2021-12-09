<?php

namespace Doofinder;

class Configuration
{
    private $host;
    private $token;

    private function __construct($host, $token, $scope)
    {
        $this->host = $host;
        $this->token = $token;
    }

    public static function create($host, $token, $scope)
    {
        return new self($host, $token, $scope);
    }

    public function getBaseUrl()
    {
        return $this->host;
    }

    public function getToken()
    {
        return $this->token;
    }
}