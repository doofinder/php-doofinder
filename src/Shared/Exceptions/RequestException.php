<?php

namespace Doofinder\Shared\Exceptions;

use Exception;

/**
 * Exception used when there is an error in the http request
 */
class RequestException extends Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param \Throwable $previous
     */
    public function __construct($message = "", $code = 0, $previous = null)
    {
        parent::__construct($message, (int)$code, $previous);
    }
}
