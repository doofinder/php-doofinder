<?php

namespace Doofinder\Shared\Exceptions;

use Exception;

/**
 * Exception used when API request returns a not OK response
 */
class ApiException extends Exception
{
    /**
     * @var mixed|null
     */
    private $body;

    /**
     * @param string $message
     * @param int $code
     * @param \Throwable $exception
     * @param mixed|null $responseBody
     */
    public function __construct($message = "", $code = 0, $exception = null, $responseBody = null)
    {
        parent::__construct($message, (int)$code, $exception);

        $this->body = $responseBody;
    }

    /**
     * @return mixed|null
     */
    public function getBody()
    {
        return $this->body;
    }
}