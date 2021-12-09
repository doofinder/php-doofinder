<?php

namespace Doofinder\Shared\Exceptions;

use Exception;

class ApiException extends Exception
{
    private $body;

    public function __construct($message = "", $code = 0, $exception = null, $responseBody = null)
    {
        parent::__construct($message, $code, $exception);

        $this->body = $responseBody;
    }

    public function getBody()
    {
        return $this->body;
    }
}