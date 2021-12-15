<?php

namespace Tests\Unit\Management;

use Doofinder\Management\ManagementClient;
use Doofinder\Management\Model\SearchEngine as SearchEngineModel;
use Doofinder\Management\Resources\Index;
use Doofinder\Management\Resources\Item;
use Doofinder\Management\Resources\SearchEngine;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpResponse;
use Doofinder\Shared\HttpStatusCode;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class ManagementClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $searchEnginesResource;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $itemsResource;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $indexesResource;

    /**
     * @var SearchEngine
     */
    private $searchEngine;

    /**
     * @var ApiException
     */
    private $unauthorizedException;

    /**
     * @var ApiException
     */
    private $badParametersException;

    /**
     * @var ApiException
     */
    private $notFoundException;

    public function setUp()
    {
        $params = [
            'hashid' => 'fake_hashid',
            'indices' => [],
            'inactive' => false,
            'currency' => 'EUR',
            'language' => 'es',
            'name' => 'test_create',
            'site_url' => 'http://test.url.com/fake',
            'search_url' => 'http://search.url.com/fake',
            'stopwords' => false,
            'platform' => 'shopify',
            'has_grouping' => false,
        ];

        /** @var SearchEngine searchEngine */
        $this->searchEngine = SearchEngineModel::createFromArray(
            $params
        );

        $this->unauthorizedException = new ApiException('', HttpStatusCode::UNAUTHORIZED);
        $this->badParametersException = new ApiException(
            '{"error": {"code" : "bad_params", "message" : "Bad parameters error"}}',
            HttpStatusCode::BAD_REQUEST
        );
        $this->notFoundException = new ApiException(
            '{"error": {"code" : "not_found"}}',
            HttpStatusCode::NOT_FOUND
        );
        $this->searchEnginesResource = $this->createMock(SearchEngine::class);
        $this->itemsResource = $this->createMock(Item::class);
        $this->indexesResource = $this->createMock(Index::class);
    }

    /**
     * @return ManagementClient
     */
    public function createSut()
    {
        return ManagementClient::createForTest(
            $this->searchEnginesResource,
            $this->itemsResource,
            $this->indexesResource
        );
    }

    public function testCreateSearchEngineNoAuthorization()
    {
        $this->searchEnginesResource
            ->expects($this->once())
            ->method('createSearchEngine')
            ->with([])
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();

        $thrownException = false;

        try {
            $managementClient->createSearchEngine([]);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::UNAUTHORIZED, $e->getCode());
            $this->assertSame(
                'The user hasn\'t provided valid authorization.',
                $e->getMessage()
            );
        }

        $this->assertTrue($thrownException);
    }

    public function invalidParamsProvider()
    {
        return [
            [[]],
            [[
                'currency' => 'fake_currency',
                'language' => 'fake_language',
                'site_url' => '',
                'stop_words' => '',
                'platform' => '',
                'has_grouping' => '',
            ]]
        ];
    }

    /**
     * @dataProvider invalidParamsProvider
     */
    public function testCreateSearchEngineInvalidParams(array $params)
    {
        $this->searchEnginesResource
            ->expects($this->once())
            ->method('createSearchEngine')
            ->with($params)
            ->willThrowException($this->badParametersException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createSearchEngine($params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            $this->assertSame('Request contains wrong parameter or values.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('bad_params', $previousMessage['code']);
            $this->assertSame('Bad parameters error', $previousMessage['message']);
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateSearchEngineSuccess()
    {
        $params = [
            'currency' => 'EUR',
            'language' => 'lslsls',
            'name' => 'test_create',
            'site_url' => 'http://test.url.com/fake',
            'stopwords' => false,
            'platform' => 'shopify',
            'has_grouping' => false,
        ];

        $httpResponse = HttpResponse::create(HttpStatusCode::CREATED);
        $httpResponse->setBody($this->searchEngine);

        $this->searchEnginesResource
            ->expects($this->once())
            ->method('createSearchEngine')
            ->with($params)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->createSearchEngine($params);

        $this->assertSame(HttpStatusCode::CREATED, $response->getStatusCode());
        $searchEngine = $response->getBody();

        $this->assertInstanceOf(SearchEngineModel::class, $searchEngine);

        $this->assertSame($this->searchEngine->getCurrency(), $searchEngine->getCurrency());
        $this->assertSame($this->searchEngine->getHashid(), $searchEngine->getHashid());
        $this->assertSame($this->searchEngine->getIndices(), $searchEngine->getIndices());
        $this->assertSame($this->searchEngine->isInactive(), $searchEngine->isInactive());
        $this->assertSame($this->searchEngine->getLanguage(), $searchEngine->getLanguage());
        $this->assertSame($this->searchEngine->getName(), $searchEngine->getName());
        $this->assertSame($this->searchEngine->getSearchUrl(), $searchEngine->getSearchUrl());
        $this->assertSame($this->searchEngine->getSiteUrl(), $searchEngine->getSiteUrl());
        $this->assertSame($this->searchEngine->isStopwords(), $searchEngine->isStopwords());
        $this->assertSame($this->searchEngine->getPlatform(), $searchEngine->getPlatform());
        $this->assertSame($this->searchEngine->isHasGrouping(), $searchEngine->isHasGrouping());
    }

    public function testUpdateSearchEngineNoAuthorization()
    {
        $this->searchEnginesResource
            ->expects($this->once())
            ->method('updateSearchEngine')
            ->with('fake_hashid', [])
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateSearchEngine('fake_hashid', []);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::UNAUTHORIZED, $e->getCode());
            $this->assertSame(
                'The user hasn\'t provided valid authorization.',
                $e->getMessage()
            );
        }

        $this->assertTrue($thrownException);
    }

    /**
     * @dataProvider invalidParamsProvider
     */
    public function testUpdateSearchEngineInvalidParams(array $params)
    {
        $hashId = 'ee859baa0f1d2d6abb7611046f297148';
        $this->searchEnginesResource
            ->expects($this->once())
            ->method('updateSearchEngine')
            ->with($hashId, $params)
            ->willThrowException($this->badParametersException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateSearchEngine($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            $this->assertSame('Request contains wrong parameter or values.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('bad_params', $previousMessage['code']);
            $this->assertSame('Bad parameters error', $previousMessage['message']);
        }

        $this->assertTrue($thrownException);
    }

    public function testUpdateSearchEngineInvalidHashid()
    {
        $hashId = 'a59357c0ea737666f41f6d6b75cbd3bc';
        $params = [
            'currency' => 'EUR',
            'language' => 'es',
            'name' => 'test_create',
            'site_url' => 'http://test.url.com/fake',
            'stopwords' => false,
            'platform' => 'shopify',
            'has_grouping' => false,
        ];

        $this->searchEnginesResource
            ->expects($this->once())
            ->method('updateSearchEngine')
            ->with($hashId, $params)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateSearchEngine($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testUpdateSearchEngineSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $managementClient = $this->createSut();
        $params = [
            'currency' => 'USD',
            'language' => 'es',
            'name' => 'another_name',
            'site_url' => 'fake_url',
            'stopwords' => true,
            'platform' => 'shopify',
            'has_grouping' => true,
        ];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody($this->searchEngine);

        $this->searchEnginesResource
            ->expects($this->once())
            ->method('updateSearchEngine')
            ->with($hashId, $params)
            ->willReturn($httpResponse);

        $response = $managementClient->updateSearchEngine($hashId, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        /** @var SearchEngineModel $searchEngine */
        $searchEngine = $response->getBody();

        $this->assertInstanceOf(SearchEngineModel::class, $searchEngine);

        $this->assertSame($this->searchEngine->getCurrency(), $searchEngine->getCurrency());
        $this->assertSame($this->searchEngine->getHashid(), $searchEngine->getHashid());
        $this->assertSame($this->searchEngine->getIndices(), $searchEngine->getIndices());
        $this->assertSame($this->searchEngine->isInactive(), $searchEngine->isInactive());
        $this->assertSame($this->searchEngine->getLanguage(), $searchEngine->getLanguage());
        $this->assertSame($this->searchEngine->getName(), $searchEngine->getName());
        $this->assertSame($this->searchEngine->getSearchUrl(), $searchEngine->getSearchUrl());
        $this->assertSame($this->searchEngine->getSiteUrl(), $searchEngine->getSiteUrl());
        $this->assertSame($this->searchEngine->isStopwords(), $searchEngine->isStopwords());
        $this->assertSame($this->searchEngine->getPlatform(), $searchEngine->getPlatform());
        $this->assertSame($this->searchEngine->isHasGrouping(), $searchEngine->isHasGrouping());

    }

    public function testGetSearchEngineNoAuthorization()
    {
        $this->searchEnginesResource
            ->expects($this->once())
            ->method('getSearchEngine')
            ->with('fake_hashid')
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->getSearchEngine('fake_hashid');
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::UNAUTHORIZED, $e->getCode());
            $this->assertSame(
                'The user hasn\'t provided valid authorization.',
                $e->getMessage()
            );
        }

        $this->assertTrue($thrownException);
    }

    public function testGetSearchEngineNotFound()
    {
        $hashId = 'f57c79f50361df29126c24543a199eae';
        $this->searchEnginesResource
            ->expects($this->once())
            ->method('getSearchEngine')
            ->with($hashId)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->getSearchEngine($hashId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testGetSearchEngineSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody($this->searchEngine);

        $this->searchEnginesResource
            ->expects($this->once())
            ->method('getSearchEngine')
            ->with($hashId)
            ->willReturn($httpResponse);
        $managementClient = $this->createSut();

        $response = $managementClient->getSearchEngine($hashId);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $searchEngine = $response->getBody();

        $this->assertInstanceOf(SearchEngineModel::class, $searchEngine);

        $this->assertSame($this->searchEngine->getCurrency(), $searchEngine->getCurrency());
        $this->assertSame($this->searchEngine->getHashid(), $searchEngine->getHashid());
        $this->assertSame($this->searchEngine->getIndices(), $searchEngine->getIndices());
        $this->assertSame($this->searchEngine->isInactive(), $searchEngine->isInactive());
        $this->assertSame($this->searchEngine->getLanguage(), $searchEngine->getLanguage());
        $this->assertSame($this->searchEngine->getName(), $searchEngine->getName());
        $this->assertSame($this->searchEngine->getSearchUrl(), $searchEngine->getSearchUrl());
        $this->assertSame($this->searchEngine->getSiteUrl(), $searchEngine->getSiteUrl());
        $this->assertSame($this->searchEngine->isStopwords(), $searchEngine->isStopwords());
        $this->assertSame($this->searchEngine->getPlatform(), $searchEngine->getPlatform());
        $this->assertSame($this->searchEngine->isHasGrouping(), $searchEngine->isHasGrouping());
    }

    public function testListSearchEngineNoAuthorization()
    {
        $this->searchEnginesResource
            ->expects($this->once())
            ->method('listSearchEngines')
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->listSearchEngines();
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::UNAUTHORIZED, $e->getCode());
            $this->assertSame(
                'The user hasn\'t provided valid authorization.',
                $e->getMessage()
            );
        }

        $this->assertTrue($thrownException);
    }

    public function testListSearchEngineSuccess()
    {
        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody([$this->searchEngine]);

        $this->searchEnginesResource
            ->expects($this->once())
            ->method('listSearchEngines')
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->listSearchEngines();

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $searchEngines = $response->getBody();

        $this->assertCount(1, $searchEngines);
        $this->assertInstanceOf(SearchEngineModel::class, $searchEngines[0]);

        $searchEngine = $searchEngines[0];
        $this->assertSame($this->searchEngine->getCurrency(), $searchEngine->getCurrency());
        $this->assertSame($this->searchEngine->getHashid(), $searchEngine->getHashid());
        $this->assertSame($this->searchEngine->getIndices(), $searchEngine->getIndices());
        $this->assertSame($this->searchEngine->isInactive(), $searchEngine->isInactive());
        $this->assertSame($this->searchEngine->getLanguage(), $searchEngine->getLanguage());
        $this->assertSame($this->searchEngine->getName(), $searchEngine->getName());
        $this->assertSame($this->searchEngine->getSearchUrl(), $searchEngine->getSearchUrl());
        $this->assertSame($this->searchEngine->getSiteUrl(), $searchEngine->getSiteUrl());
        $this->assertSame($this->searchEngine->isStopwords(), $searchEngine->isStopwords());
        $this->assertSame($this->searchEngine->getPlatform(), $searchEngine->getPlatform());
        $this->assertSame($this->searchEngine->isHasGrouping(), $searchEngine->isHasGrouping());
    }

    public function testDeleteSearchEngineNoAuthorization()
    {
        $this->searchEnginesResource
            ->expects($this->once())
            ->method('deleteSearchEngine')
            ->with('fake_hashid')
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->deleteSearchEngine('fake_hashid');
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::UNAUTHORIZED, $e->getCode());
            $this->assertSame(
                'The user hasn\'t provided valid authorization.',
                $e->getMessage()
            );
        }

        $this->assertTrue($thrownException);
    }

    public function testDeleteSearchEngineNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $this->searchEnginesResource
            ->expects($this->once())
            ->method('deleteSearchEngine')
            ->with($hashId)
            ->willThrowException($this->notFoundException);
        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->deleteSearchEngine($hashId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testDeleteSearchEngineSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $httpResponse = HttpResponse::create(HttpStatusCode::NO_CONTENT);

        $this->searchEnginesResource
            ->expects($this->once())
            ->method('deleteSearchEngine')
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();

        $response = $managementClient->deleteSearchEngine($hashId);

        $this->assertSame(HttpStatusCode::NO_CONTENT, $response->getStatusCode());
    }
}