<?php

namespace Tests\Unit\Shared\Utils;

use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\Interfaces\HttpResponseInterface;
use Doofinder\Shared\Utils\ErrorHandler;
use PHPUnit_Framework_TestCase;
use Exception;

class ErrorHandlerTest extends PHPUnit_Framework_TestCase
{
    public function errorsProvider()
    {
        return [
            [HttpResponseInterface::STATUS_BAD_REQUEST, 'Request contains wrong parameter or values.', 'bad_params'],
            [HttpResponseInterface::STATUS_BAD_REQUEST, 'Request contains wrong parameter or values.', 'index_internal_error'],
            [HttpResponseInterface::STATUS_BAD_REQUEST, 'Invalid value for item boost field.', 'invalid_boost_value'],
            [HttpResponseInterface::STATUS_BAD_REQUEST, 'Items field names contains invalid characters.', 'invalid_field_name'],
            [HttpResponseInterface::STATUS_BAD_REQUEST, 'The client made a bad request.'],
            [HttpResponseInterface::STATUS_UNAUTHORIZED, 'The user hasn\'t provided valid authorization.'],
            [HttpResponseInterface::STATUS_FORBIDDEN, 'The user does not have permissions to perform this operation.'],
            [HttpResponseInterface::STATUS_NOT_FOUND, 'Not Found.'],
            [HttpResponseInterface::STATUS_TIMEOUT, 'Operation has surpassed time limit.'],
            [HttpResponseInterface::STATUS_CONFLICT, 'The request search engine is locked by another operation.', 'searchengine_locked'],
            [HttpResponseInterface::STATUS_CONFLICT, 'There are too many temporary index.', 'too_many_temporary'],
            [HttpResponseInterface::STATUS_CONFLICT, 'Request conflict.'],
            [HttpResponseInterface::STATUS_ENTITY_TOO_LARGE, 'Requests contains too many items.'],
            [HttpResponseInterface::STATUS_TOO_MANY_REQUESTS, 'Too many requests by second.'],
            [HttpResponseInterface::STATUS_INTERNAL_SERVER_ERROR, 'Server error.'],
            [HttpResponseInterface::STATUS_BAD_GATEWAY, 'Bad Gateway Error connecting to Doofinder.'],
            [555, 'Unknown error'],
        ];
    }

    /**
     * @dataProvider errorsProvider
     */
    public function testCreate($statusCode, $expectedMessage, $errorCode = null)
    {
        $response = '{"error": {"code":"' . $errorCode . '"}}';
        $exception = $this->createMock(Exception::class);

        /** @var ApiException $apiException */
        $apiException = ErrorHandler::create($statusCode, $response, $exception);

        $this->assertSame($expectedMessage, $apiException->getMessage());
        $this->assertSame($exception, $apiException->getPrevious());
    }
}