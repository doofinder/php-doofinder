<?php

namespace Doofinder\Shared\Interfaces;

interface HttpResponseInterface
{

    const STATUS_OK = 200;
    const STATUS_CREATED = 201;
    const STATUS_NO_CONTENT = 204;

    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;
    const STATUS_TIMEOUT = 408;
    const STATUS_CONFLICT = 409;
    const STATUS_ENTITY_TOO_LARGE = 413;
    const STATUS_TOO_MANY_REQUESTS = 429;
    const STATUS_INTERNAL_SERVER_ERROR = 500;
    const STATUS_BAD_GATEWAY = 502;

    public static function create($statusCode, $body = null);
    public function getBody();
    public function getStatusCode();
    public function setBody($body);
}