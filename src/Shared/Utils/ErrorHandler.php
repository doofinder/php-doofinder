<?php

namespace Doofinder\Shared\Utils;

use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpStatusCode;

/**
 * Class in charge on mapping response message to client message
 */
class ErrorHandler
{
    const DEFAULT_FIELD = 'default';
    const MESSAGES = [
        HttpStatusCode::BAD_REQUEST => [
            'bad_params' => 'Request contains wrong parameter or values.',
            'index_internal_error' => 'Request contains wrong parameter or values.',
            'invalid_boost_value' => 'Invalid value for item boost field.',
            'invalid_field_name' => 'Items field names contains invalid characters.',
            self::DEFAULT_FIELD => 'The client made a bad request.'
        ],
        HttpStatusCode::UNAUTHORIZED => 'The user hasn\'t provided valid authorization.',
        HttpStatusCode::FORBIDDEN => 'The user does not have permissions to perform this operation.',
        HttpStatusCode::NOT_FOUND => 'Not Found.',
        HttpStatusCode::REQUEST_TIMEOUT => 'Operation has surpassed time limit.',
        HttpStatusCode::CONFLICT => [
            'searchengine_locked' => 'The request search engine is locked by another operation.',
            'too_many_temporary' => 'There are too many temporary index.',
            self::DEFAULT_FIELD => 'Request conflict.'
        ],
        HttpStatusCode::REQUEST_ENTITY_TOO_LARGE => 'Requests contains too many items.',
        HttpStatusCode::TOO_MANY_REQUESTS => 'Too many requests by second.',
        HttpStatusCode::INTERNAL_SERVER_ERROR => 'Server error.',
        HttpStatusCode::BAD_GATEWAY => 'Bad Gateway Error connecting to Doofinder.',
        self::DEFAULT_FIELD => 'Unknown error'
    ];

    /**
     * @param int $statusCode
     * @param string $response
     * @param \Throwable $exception
     * @return ApiException
     */
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

    /**
     * @param string $response
     * @return string|array $headers
     */
    private static function getErrorCode($response)
    {
        $error = json_decode($response, true)["error"];

        if (is_array($error) && array_key_exists('code', $error)) {
            $error = $error['code'];
        }

        return $error;
    }
}