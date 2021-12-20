<?php

namespace Tests\Unit\Management\Resources;

use Doofinder\Management\Model\SearchEngine as SearchEngineModel;
use Doofinder\Management\Resources\SearchEngine;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpResponse;
use Doofinder\Shared\HttpStatusCode;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Shared\Interfaces\HttpResponseInterface;

class SearchEngineTest extends BaseResourceTest
{
    public function createSut()
    {
        return SearchEngine::create($this->httpClient, $this->config);
    }


    private function getUrl($hashId = null)
    {
        return self::BASE_URL . '/search_engines' . (!is_null($hashId)? '/' . $hashId : '');
    }

    public function testCreateSearchEngineSuccess()
    {
        $body = [
            'language' => 'es',
            'name' => 'test_create',
            'currency' => 'EUR',
            'hashid' => 'fake_hashid',
            'indices' => [],
            'inactive' => false,
            'search_url' => 'fake_search_url',
            'site_url' => 'http://test.url.com/fake',
            'stopwords' => false,
            'platform' => 'shopify',
            'has_grouping' => false
        ];

        $response = HttpResponse::create(HttpStatusCode::CREATED, json_encode($body));
        $params = [
            'currency' => 'EUR',
            'language' => 'es',
            'name' => 'test_create',
            'site_url' => 'http://test.url.com/fake',
            'stopwords' => false,
            'platform' => 'shopify',
            'has_grouping' => false,
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl(), HttpClientInterface::METHOD_POST, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->createSearchEngine($params);

        $this->assertSame(HttpStatusCode::CREATED, $response->getStatusCode());
        $this->assertInstanceOf(SearchEngineModel::class, $response->getBody());

        /** @var SearchEngineModel $searchEngine */
        $searchEngine = $response->getBody();
        $this->assertSame($searchEngine->jsonSerialize(), $body);
    }

    public function testCreateSearchEngineInvalidParams()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error" : {"code": "bad_params"}}');
        $params = [];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl(), HttpClientInterface::METHOD_POST, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->createSearchEngine($params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('bad_params', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateSearchEngineErrorWithNoMessage()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error": {"code" : "Something went wrong"}}');
        $params = [];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl(), HttpClientInterface::METHOD_POST, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->createSearchEngine($params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('Something went wrong', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testUpdateSearchEngine()
    {
        $hashId = 'fake_hashid';

        $body = [
            'language' => 'es',
            'name' => 'test_update',
            'currency' => 'EUR',
            'hashid' => $hashId,
            'indices' => [],
            'inactive' => false,
            'search_url' => 'fake_search_url',
            'site_url' => 'http://test.url.com/fake',
            'stopwords' => false,
            'platform' => 'shopify',
            'has_grouping' => false
        ];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));
        $params = [
            'currency' => 'EUR',
            'language' => 'es',
            'name' => 'test_update',
            'site_url' => 'http://test.url.com/fake',
            'stopwords' => false,
            'platform' => 'shopify',
            'has_grouping' => false,
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId), HttpClientInterface::METHOD_PATCH, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->updateSearchEngine($hashId, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertInstanceOf(SearchEngineModel::class, $response->getBody());

        /** @var SearchEngineModel $searchEngine */
        $searchEngine = $response->getBody();
        $this->assertSame($searchEngine->jsonSerialize(), $body);
    }

    public function testUpdateSearchEngineNotFound()
    {
        $hashId = 'fake_hashid';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');
        $params = [];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId), HttpClientInterface::METHOD_PATCH, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->updateSearchEngine($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testGetSearchEngine()
    {
        $hashId = 'fake_hashid';

        $body = [
            'language' => 'es',
            'name' => 'test_get',
            'currency' => 'EUR',
            'hashid' => $hashId,
            'indices' => [],
            'inactive' => false,
            'search_url' => 'fake_search_url',
            'site_url' => 'http://test.url.com/fake',
            'stopwords' => false,
            'platform' => 'shopify',
            'has_grouping' => false
        ];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId), HttpClientInterface::METHOD_GET, [], $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->getSearchEngine($hashId);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertInstanceOf(SearchEngineModel::class, $response->getBody());

        /** @var SearchEngineModel $searchEngine */
        $searchEngine = $response->getBody();
        $this->assertSame($searchEngine->jsonSerialize(), $body);
    }

    public function testGetSearchEngineNotFound()
    {
        $hashId = 'fake_hashid';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId), HttpClientInterface::METHOD_GET, [], $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->getSearchEngine($hashId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testListSearchEngine()
    {
        $hashId = 'fake_hashid';

        $body = [[
            'language' => 'es',
            'name' => 'test_list',
            'currency' => 'EUR',
            'hashid' => $hashId,
            'indices' => [],
            'inactive' => false,
            'search_url' => 'fake_search_url',
            'site_url' => 'http://test.url.com/fake',
            'stopwords' => false,
            'platform' => 'shopify',
            'has_grouping' => false
        ]];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl(), HttpClientInterface::METHOD_GET, [], $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->listSearchEngines();

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());

        /** @var array<SearchEngineModel> $searchEngines */
        $searchEngines = $response->getBody();
        $this->assertCount(1, $searchEngines);;
        $this->assertInstanceOf(SearchEngineModel::class, $searchEngines[0]);
        $this->assertSame($searchEngines[0]->jsonSerialize(), $body[0]);
    }

    public function testDeleteSearchEngine()
    {
        $hashId = 'fake_hashid';

        $response = HttpResponse::create(HttpStatusCode::NO_CONTENT);

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId), HttpClientInterface::METHOD_DELETE, [], $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->deleteSearchEngine($hashId);

        $this->assertSame(HttpStatusCode::NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteSearchEngineNotFound()
    {
        $hashId = 'fake_hashid';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId), HttpClientInterface::METHOD_DELETE, [], $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->deleteSearchEngine($hashId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }
}