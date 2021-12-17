<?php

namespace Tests\Unit\Shared\Utils;

use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpStatusCode;
use Doofinder\Shared\Utils\ErrorHandler;
use PHPUnit_Framework_TestCase;
use Exception;

class ErrorHandlerTest extends PHPUnit_Framework_TestCase
{
    public function errorsProvider()
    {
        return [
            [HttpStatusCode::BAD_REQUEST, 'Request contains wrong parameter or values.', 'bad_params'],
            [HttpStatusCode::BAD_REQUEST, 'Request contains wrong parameter or values.', 'index_internal_error'],
            [HttpStatusCode::BAD_REQUEST, 'Invalid value for item boost field.', 'invalid_boost_value'],
            [HttpStatusCode::BAD_REQUEST, 'Items field names contains invalid characters.', 'invalid_field_name'],
            [HttpStatusCode::BAD_REQUEST, 'The client made a bad request.'],
            [HttpStatusCode::UNAUTHORIZED, 'The user hasn\'t provided valid authorization.'],
            [HttpStatusCode::FORBIDDEN, 'The user does not have permissions to perform this operation.'],
            [HttpStatusCode::NOT_FOUND, 'Not Found.'],
            [HttpStatusCode::REQUEST_TIMEOUT, 'Operation has surpassed time limit.'],
            [HttpStatusCode::CONFLICT, 'The request search engine is locked by another operation.', 'searchengine_locked'],
            [HttpStatusCode::CONFLICT, 'There are too many temporary index.', 'too_many_temporary'],
            [HttpStatusCode::CONFLICT, 'Request conflict.'],
            [HttpStatusCode::REQUEST_ENTITY_TOO_LARGE, 'Requests contains too many items.'],
            [HttpStatusCode::TOO_MANY_REQUESTS, 'Too many requests by second.'],
            [HttpStatusCode::INTERNAL_SERVER_ERROR, 'Server error.'],
            [HttpStatusCode::BAD_GATEWAY, 'Bad Gateway Error connecting to Doofinder.'],
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