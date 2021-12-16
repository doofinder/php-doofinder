<?php

namespace Doofinder;

/**
 * Class with API configuration
 */
class Configuration
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $userId;

    /**
     * @param string $host
     * @param string $token
     * @param string $userId
     */
    private function __construct($host, $token, $userId)
    {
        $this->host = $host;
        $this->token = $token;
        $this->userId = $userId;
    }

    /**
     * @param string $host
     * @param string $token
     * @param string $userId
     * @return Configuration
     */
    public static function create($host, $token, $userId)
    {
        return new self($host, $token, $userId);
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }
}