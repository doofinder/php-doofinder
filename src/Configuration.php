<?php

namespace Doofinder;

class Configuration
{
    private $host;
    private $token;
    private $userId;

    private function __construct($host, $token, $userId)
    {
        $this->host = $host;
        $this->token = $token;
        $this->userId = $userId;
    }

    public static function create($host, $token, $userId)
    {
        return new self($host, $token, $userId);
    }

    public function getBaseUrl()
    {
        return $this->host;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}