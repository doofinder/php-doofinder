<?php

namespace Tests\Unit\Search;

use Doofinder\Search\Resources\Search;
use Doofinder\Search\Resources\Stats;
use Doofinder\Search\SearchClient;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpResponse;
use Doofinder\Shared\HttpStatusCode;

class SearchClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Search
     */
    private $searchResource;

    /**
     * @var Stats
     */
    private $statsResource;

    /**
     * @var ApiException
     */
    protected $unauthorizedException;

    /**
     * @var ApiException
     */
    protected $badParametersException;

    /**
     * @var ApiException
     */
    protected $notFoundException;

    protected function setUp()
    {
        parent::setUp();

        $this->searchResource = $this->createMock(Search::class);
        $this->statsResource = $this->createMock(Stats::class);

        $this->unauthorizedException = new ApiException('', HttpStatusCode::UNAUTHORIZED);
        $this->badParametersException = new ApiException(
            '{"error": {"code" : "bad_params", "message" : "Bad parameters error"}}',
            HttpStatusCode::BAD_REQUEST
        );
        $this->notFoundException = new ApiException(
            '{"error": {"code" : "not_found"}}',
            HttpStatusCode::NOT_FOUND
        );
    }

    private function createSut()
    {
        return new SearchClient(
            $this->searchResource,
            $this->statsResource
        );
    }

    public function testSearchSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $params = [
            'query' => 'pepito',
        ];
        $body = [
            'count' => 0,
            'facets' => [],
            'query_name' => 'fuzzy',
            'results' => [],
            'total' => 0
        ];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->searchResource
            ->expects($this->once())
            ->method('search')
            ->with($hashId, $params)
            ->willReturn($httpResponse);

        $searchClient = $this->createSut();
        $response = $searchClient->search($hashId, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertSame($body, $response->getBody());
    }

    public function testSearchNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $params = [];
        $forbiddenException = new ApiException('', HttpStatusCode::FORBIDDEN);

        $this->searchResource
            ->expects($this->once())
            ->method('search')
            ->with($hashId, $params)
            ->willThrowException($forbiddenException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->search($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::FORBIDDEN, $e->getCode());
            $this->assertSame('The user does not have permissions to perform this operation.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testSearchInvalidParams()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $params = [];

        $badParametersException = new ApiException(
            '{"error":  "Following fields are missing: [\"query\"]"}',
            HttpStatusCode::BAD_REQUEST
        );

        $this->searchResource
            ->expects($this->once())
            ->method('search')
            ->with($hashId, $params)
            ->willThrowException($badParametersException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->search($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            $this->assertSame('The client made a bad request.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testSearchNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $params = [];

        $this->searchResource
            ->expects($this->once())
            ->method('search')
            ->with($hashId, $params)
            ->willThrowException($this->notFoundException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->search($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testSuggestSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $params = [
            'indices' => ['test'],
            'query' => 'iphone c',
            'stats' => 'false',
        ];

        $body = [
            'iphone case',
            'iphone'
        ];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->searchResource
            ->expects($this->once())
            ->method('suggest')
            ->with($hashId, $params)
            ->willReturn($httpResponse);

        $searchClient = $this->createSut();
        $response = $searchClient->suggest($hashId, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertSame($body, $response->getBody());
    }

    public function testSuggestNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $params = [];
        $forbiddenException = new ApiException('', HttpStatusCode::FORBIDDEN);

        $this->searchResource
            ->expects($this->once())
            ->method('suggest')
            ->with($hashId, $params)
            ->willThrowException($forbiddenException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->suggest($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::FORBIDDEN, $e->getCode());
            $this->assertSame('The user does not have permissions to perform this operation.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testSuggestInvalidParams()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $params = [];

        $badParametersException = new ApiException(
            '{"error":  "Following fields are missing: [\"query\"]"}',
            HttpStatusCode::BAD_REQUEST
        );

        $this->searchResource
            ->expects($this->once())
            ->method('suggest')
            ->with($hashId, $params)
            ->willThrowException($badParametersException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->suggest($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            $this->assertSame('The client made a bad request.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testSuggestNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $params = [];

        $this->searchResource
            ->expects($this->once())
            ->method('suggest')
            ->with($hashId, $params)
            ->willThrowException($this->notFoundException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->suggest($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testInitSessionSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $body = ['status' => 'registered'];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->statsResource
            ->expects($this->once())
            ->method('initSession')
            ->with($hashId, $sessionId)
            ->willReturn($httpResponse);

        $searchClient = $this->createSut();
        $response = $searchClient->initSession($hashId, $sessionId);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertSame($body, $response->getBody());
    }

    public function testInitSessionNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $forbiddenException = new ApiException('', HttpStatusCode::FORBIDDEN);

        $this->statsResource
            ->expects($this->once())
            ->method('initSession')
            ->with($hashId, $sessionId)
            ->willThrowException($forbiddenException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->initSession($hashId, $sessionId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::FORBIDDEN, $e->getCode());
            $this->assertSame('The user does not have permissions to perform this operation.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testInitSessionNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';

        $this->statsResource
            ->expects($this->once())
            ->method('initSession')
            ->with($hashId, $sessionId)
            ->willThrowException($this->notFoundException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->initSession($hashId, $sessionId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testLogCheckoutSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $body = ['status' => 'registered'];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->statsResource
            ->expects($this->once())
            ->method('logCheckout')
            ->with($hashId, $sessionId)
            ->willReturn($httpResponse);

        $searchClient = $this->createSut();
        $response = $searchClient->logCheckout($hashId, $sessionId);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertSame($body, $response->getBody());
    }

    public function testLogCheckoutNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $forbiddenException = new ApiException('', HttpStatusCode::FORBIDDEN);

        $this->statsResource
            ->expects($this->once())
            ->method('logCheckout')
            ->with($hashId, $sessionId)
            ->willThrowException($forbiddenException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->logCheckout($hashId, $sessionId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::FORBIDDEN, $e->getCode());
            $this->assertSame('The user does not have permissions to perform this operation.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testLogCheckoutNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';

        $this->statsResource
            ->expects($this->once())
            ->method('logCheckout')
            ->with($hashId, $sessionId)
            ->willThrowException($this->notFoundException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->logCheckout($hashId, $sessionId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testLogRedirectionSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $body = ['status' => 'registered'];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));
        $id = 'fake_id';
        $query = 'fake_query';

        $this->statsResource
            ->expects($this->once())
            ->method('logRedirection')
            ->with($hashId, $sessionId, $id, $query)
            ->willReturn($httpResponse);

        $searchClient = $this->createSut();
        $response = $searchClient->logRedirection($hashId, $sessionId, $id, $query);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertSame($body, $response->getBody());
    }

    public function testLogRedirectionNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $forbiddenException = new ApiException('', HttpStatusCode::FORBIDDEN);
        $id = 'fake_id';
        $query = 'fake_query';

        $this->statsResource
            ->expects($this->once())
            ->method('logRedirection')
            ->with($hashId, $sessionId, $id, $query)
            ->willThrowException($forbiddenException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->logRedirection($hashId, $sessionId, $id, $query);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::FORBIDDEN, $e->getCode());
            $this->assertSame('The user does not have permissions to perform this operation.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testLogRedirectionNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $id = 'fake_id';
        $query = 'fake_query';

        $this->statsResource
            ->expects($this->once())
            ->method('logRedirection')
            ->with($hashId, $sessionId, $id, $query)
            ->willThrowException($this->notFoundException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->logRedirection($hashId, $sessionId, $id, $query);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testLogBannerSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $body = ['status' => 'registered'];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));
        $id = 'fake_id';
        $query = 'fake_query';

        $this->statsResource
            ->expects($this->once())
            ->method('logBanner')
            ->with($hashId, $sessionId, $id, $query)
            ->willReturn($httpResponse);

        $searchClient = $this->createSut();
        $response = $searchClient->logBanner($hashId, $sessionId, $id, $query);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertSame($body, $response->getBody());
    }

    public function testLogBannerNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $forbiddenException = new ApiException('', HttpStatusCode::FORBIDDEN);
        $id = 'fake_id';
        $query = 'fake_query';

        $this->statsResource
            ->expects($this->once())
            ->method('logBanner')
            ->with($hashId, $sessionId, $id, $query)
            ->willThrowException($forbiddenException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->logBanner($hashId, $sessionId, $id, $query);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::FORBIDDEN, $e->getCode());
            $this->assertSame('The user does not have permissions to perform this operation.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testLogBannerNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $id = 'fake_id';
        $query = 'fake_query';

        $this->statsResource
            ->expects($this->once())
            ->method('logBanner')
            ->with($hashId, $sessionId, $id, $query)
            ->willThrowException($this->notFoundException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->logBanner($hashId, $sessionId, $id, $query);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testLogClickSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $body = ['status' => 'registered'];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));
        $itemId = 'fake_id';
        $query = 'fake_query';

        $this->statsResource
            ->expects($this->once())
            ->method('logClick')
            ->with($hashId, $sessionId, $itemId, $query)
            ->willReturn($httpResponse);

        $searchClient = $this->createSut();
        $response = $searchClient->logClick($hashId, $sessionId, $itemId, $query);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertSame($body, $response->getBody());
    }

    public function testLogClickNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $forbiddenException = new ApiException('', HttpStatusCode::FORBIDDEN);
        $itemId = 'fake_id';
        $query = 'fake_query';

        $this->statsResource
            ->expects($this->once())
            ->method('logClick')
            ->with($hashId, $sessionId, $itemId, $query)
            ->willThrowException($forbiddenException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->logClick($hashId, $sessionId, $itemId, $query);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::FORBIDDEN, $e->getCode());
            $this->assertSame('The user does not have permissions to perform this operation.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testLogClickNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $itemId = 'fake_id';
        $query = 'fake_query';

        $this->statsResource
            ->expects($this->once())
            ->method('logClick')
            ->with($hashId, $sessionId, $itemId, $query)
            ->willThrowException($this->notFoundException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->logClick($hashId, $sessionId, $itemId, $query);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testLogAddToCartSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $body = ['status' => 'registered'];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));
        $id = 'fake_id';
        $amount = 2;
        $indexName = 'fake_index';
        $price = 123.56;
        $title = 'fake_title';

        $this->statsResource
            ->expects($this->once())
            ->method('logAddToCart')
            ->with($hashId, $sessionId, $amount, $id, $indexName, $price, $title)
            ->willReturn($httpResponse);

        $searchClient = $this->createSut();
        $response = $searchClient->logAddToCart($hashId, $sessionId, $amount, $id, $indexName, $price, $title);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertSame($body, $response->getBody());
    }

    public function testLogAddToCartNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $forbiddenException = new ApiException('', HttpStatusCode::FORBIDDEN);
        $id = 'fake_id';
        $amount = 2;
        $indexName = 'fake_index';
        $price = 123.56;
        $title = 'fake_title';

        $this->statsResource
            ->expects($this->once())
            ->method('logAddToCart')
            ->with($hashId, $sessionId, $amount, $id, $indexName, $price, $title)
            ->willThrowException($forbiddenException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->logAddToCart($hashId, $sessionId, $amount, $id, $indexName, $price, $title);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::FORBIDDEN, $e->getCode());
            $this->assertSame('The user does not have permissions to perform this operation.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testLogAddToCartNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $id = 'fake_id';
        $amount = 2;
        $indexName = 'fake_index';
        $price = 123.56;
        $title = 'fake_title';

        $this->statsResource
            ->expects($this->once())
            ->method('logAddToCart')
            ->with($hashId, $sessionId, $amount, $id, $indexName, $price, $title)
            ->willThrowException($this->notFoundException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->logAddToCart($hashId, $sessionId, $amount, $id, $indexName, $price, $title);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testLogDeleteFromCartSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $body = ['status' => 'registered'];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));
        $id = 'fake_id';
        $amount = 2;
        $indexName = 'fake_index';

        $this->statsResource
            ->expects($this->once())
            ->method('logRemoveFromCart')
            ->with($hashId, $sessionId, $amount, $id, $indexName)
            ->willReturn($httpResponse);

        $searchClient = $this->createSut();
        $response = $searchClient->logRemoveFromCart($hashId, $sessionId, $amount, $id, $indexName);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertSame($body, $response->getBody());
    }

    public function testLogDeleteFromCartNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $id = 'fake_id';
        $amount = 2;
        $indexName = 'fake_index';
        $forbiddenException = new ApiException('', HttpStatusCode::FORBIDDEN);

        $this->statsResource
            ->expects($this->once())
            ->method('logRemoveFromCart')
            ->with($hashId, $sessionId, $amount, $id, $indexName)
            ->willThrowException($forbiddenException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->logRemoveFromCart($hashId, $sessionId, $amount, $id, $indexName);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::FORBIDDEN, $e->getCode());
            $this->assertSame('The user does not have permissions to perform this operation.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testLogDeleteFromCartNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $id = 'fake_id';
        $amount = 2;
        $indexName = 'fake_index';

        $this->statsResource
            ->expects($this->once())
            ->method('logRemoveFromCart')
            ->with($hashId, $sessionId, $amount, $id, $indexName)
            ->willThrowException($this->notFoundException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->logRemoveFromCart($hashId, $sessionId, $amount, $id, $indexName);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testClearCartSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $body = ['status' => 'registered'];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->statsResource
            ->expects($this->once())
            ->method('clearCart')
            ->with($hashId, $sessionId)
            ->willReturn($httpResponse);

        $searchClient = $this->createSut();
        $response = $searchClient->clearCart($hashId, $sessionId);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertSame($body, $response->getBody());
    }

    public function testClearCartNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $forbiddenException = new ApiException('', HttpStatusCode::FORBIDDEN);

        $this->statsResource
            ->expects($this->once())
            ->method('clearCart')
            ->with($hashId, $sessionId)
            ->willThrowException($forbiddenException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->clearCart($hashId, $sessionId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::FORBIDDEN, $e->getCode());
            $this->assertSame('The user does not have permissions to perform this operation.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testClearCartNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';

        $this->statsResource
            ->expects($this->once())
            ->method('clearCart')
            ->with($hashId, $sessionId)
            ->willThrowException($this->notFoundException);

        $searchClient = $this->createSut();
        $thrownException = false;

        try {
            $searchClient->clearCart($hashId, $sessionId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }
}