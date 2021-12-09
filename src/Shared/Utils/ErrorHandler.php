<?php

namespace Doofinder\Shared\Utils;

use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\Interfaces\HttpResponseInterface;

class ErrorHandler
{
    const DEFAULT_FIELD = 'default';
    const MESSAGES = [
        HttpResponseInterface::STATUS_BAD_REQUEST => [
            'bad_params' => 'Request contains wrong parameter or values.',
            'index_internal_error' => 'Request contains wrong parameter or values.',
            'invalid_boost_value' => 'Invalid value for item boost field.',
            'invalid_field_name' => 'Items field names contains invalid characters.',
            self::DEFAULT_FIELD => 'The client made a bad request.'
        ],
        HttpResponseInterface::STATUS_UNAUTHORIZED => 'The user hasn\'t provided valid authorization.',
        HttpResponseInterface::STATUS_FORBIDDEN => 'The user does not have permissions to perform this operation.',
        HttpResponseInterface::STATUS_NOT_FOUND => 'Not Found.',
        HttpResponseInterface::STATUS_TIMEOUT => 'Operation has surpassed time limit.',
        HttpResponseInterface::STATUS_CONFLICT => [
            'searchengine_locked' => 'The request search engine is locked by another operation.',
            'too_many_temporary' => 'There are too many temporary index.',
            self::DEFAULT_FIELD => 'Request conflict.'
        ],
        HttpResponseInterface::STATUS_ENTITY_TOO_LARGE => 'Requests contains too many items.',
        HttpResponseInterface::STATUS_TOO_MANY_REQUESTS => 'Too many requests by second.',
        HttpResponseInterface::STATUS_INTERNAL_SERVER_ERROR => 'Server error.',
        HttpResponseInterface::STATUS_BAD_GATEWAY => 'Bad Gateway Error connecting to Doofinder.',
        self::DEFAULT_FIELD => 'Unknown error'
    ];

    public static function create($statusCode, $response, $exception = null)
    {
        $message = array_key_exists($statusCode, self::MESSAGES)?
            self::MESSAGES[$statusCode] : self::MESSAGES[self::DEFAULT_FIELD];

        if (is_array($message)) {
            $message = array_key_exists(ErrorHandler::getErrorCode($response), $message)?
                $message[ErrorHandler::getErrorCode($response)] : $message[self::DEFAULT_FIELD];
        }

        return new ApiException($message, $statusCode, $exception, $response);
    }

    private static function getErrorCode($response)
    {
        return json_decode($response, true)["error"]['code'];
    }
}