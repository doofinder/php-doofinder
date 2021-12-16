<?php

namespace Doofinder\Shared;

use Doofinder\Shared\Interfaces\HttpResponseInterface;

/**
 * Class to return a formatted info comming from API
 */
class HttpResponse implements HttpResponseInterface
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var mixed
     */
    private $body;

    /**
     * @param $statusCode
     * @param $body
     */
    private function __construct($statusCode, $body)
    {
        $this->statusCode = $statusCode;
        $this->body = json_decode($body, true);
    }

    /**
     * @param int $statusCode
     * @param mixed $body
     * @return HttpResponse
     */
    public static function create($statusCode, $body = null)
    {
        return new self($statusCode, $body);
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
}