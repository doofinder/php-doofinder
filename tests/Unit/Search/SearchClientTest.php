<?php

namespace Tests\Unit\Search;

use Doofinder\Search\Resources\Search;
use Doofinder\Search\Resources\Stat;
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
     * @var Stat
     */
    private $statResource;

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
        $this->statResource = $this->createMock(Stat::class);

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
            $this->statResource
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

    public function testStatsSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $body = ['status' => 'registered'];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->statResource
            ->expects($this->once())
            ->method('initSession')
            ->with($hashId, $sessionId)
            ->willReturn($httpResponse);

        $searchClient = $this->createSut();
        $response = $searchClient->initSession($hashId, $sessionId);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertSame($body, $response->getBody());
    }

    public function testStatsNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $forbiddenException = new ApiException('', HttpStatusCode::FORBIDDEN);

        $this->statResource
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

    public function testStatsNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';

        $this->statResource
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

    public function testLogRedirectionSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'fake_session_id';
        $body = ['status' => 'registered'];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));
        $id = 'fake_id';
        $query = 'fake_query';

        $this->statResource
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

        $this->statResource
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

        $this->statResource
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

        $this->statResource
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

        $this->statResource
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

        $this->statResource
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
}