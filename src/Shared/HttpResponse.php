<?php

namespace Doofinder\Shared;

use Doofinder\Shared\Interfaces\HttpResponseInterface;

class HttpResponse implements HttpResponseInterface
{
    private $statusCode;
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
     * @param mixed $statusCode
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
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return mixed
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
}