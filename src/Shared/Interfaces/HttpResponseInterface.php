<?php

namespace Doofinder\Shared\Interfaces;

/**
 * Http response's interface
 */
interface HttpResponseInterface
{
    /**
     * @param int $statusCode
     * @param mixed $body
     * @return mixed
     */
    public static function create($statusCode, $body = null);

    /**
     * @return mixed
     */
    public function getBody();

    /**
     * @return int
     */
    public function getStatusCode();

    /**
     * @param mixed $body
     */
    public function setBody($body);
}