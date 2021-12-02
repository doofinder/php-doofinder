<?php

namespace Doofinder;

class Configuration
{
    private $host;
    private $token;
    private $validationsPath;

    private function __construct($host, $token, $scope)
    {
        $this->host = $host;
        $this->token = $token;
        $this->validationsPath = __DIR__ . '/' . $scope . '/Validations';
    }

    public static function create($host, $token, $scope)
    {
        return new self($host, $token, $scope);
    }

    public function getBaseUrl()
    {
        return $this->host;
    }

    public function getValidationsPath()
    {
        return $this->validationsPath;
    }
}