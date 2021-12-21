<?php

namespace Tests\Unit\Management\Resources;

use Doofinder\Management\Resources\Index;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpResponse;
use Doofinder\Shared\HttpStatusCode;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Management\Model\Index as IndexModel;
use Doofinder\Shared\Interfaces\HttpResponseInterface;

class IndexTest extends BaseResourceTest
{
    private function createSut()
    {
        return Index::create($this->httpClient, $this->config);
    }

    private function getUrl($hashId, $indexName = null)
    {
        return self::BASE_URL . '/search_engines/' . $hashId . '/indices' . (!is_null($indexName)? '/' . $indexName : '');
    }

    public function testCreateIndexSuccess()
    {
        $params = [
            'name' => 'test name',
            'preset' => 'generic',
            'options' => ['exclude_out_of_stock_items' => true],
            'datasources' => [
                [
                    'type' => 'bigcommerce',
                    'options' => [
                        'access_token' => 'fake_access_token',
                        'store_hash' => 'fake_store_hash',
                        'url' => 'fake_url'
                    ]
                ]
            ],
        ];

        $response = HttpResponse::create(HttpStatusCode::CREATED, json_encode($params));

        $hashId = '3a0811e861d36f76cedca60723e03291';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId), HttpClientInterface::METHOD_POST, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->createIndex($hashId, $params);

        $this->assertSame(HttpStatusCode::CREATED, $response->getStatusCode());
        $this->assertInstanceOf(IndexModel::class, $response->getBody());

        /** @var IndexModel $index */
        $index = $response->getBody();
        $this->assertEquals($index->jsonSerialize(), $params);
    }

    public function testCreateIndexInvalidParams()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error" : {"code": "bad_params"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $params = [];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId), HttpClientInterface::METHOD_POST, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->createIndex($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('bad_params', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateIndexErrorWithNoMessage()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error": {"code" : "Something went wrong"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $params = [];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId), HttpClientInterface::METHOD_POST, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->createIndex($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('Something went wrong', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testUpdateIndex()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $params = [
            'name' => 'test name',
            'preset' => 'generic',
            'options' => ['exclude_out_of_stock_items' => true],
            'datasources' => [
                [
                    'type' => 'bigcommerce',
                    'options' => [
                        'access_token' => 'fake_access_token',
                        'store_hash' => 'fake_store_hash',
                        'url' => 'fake_url'
                    ]
                ]
            ],
        ];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($params));

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName), HttpClientInterface::METHOD_PATCH, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->updateIndex($hashId, $indexName, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertInstanceOf(IndexModel::class, $response->getBody());

        /** @var IndexModel $index */
        $index = $response->getBody();
        $this->assertEquals($index->jsonSerialize(), $params);
    }

    public function testUpdateIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');
        $params = [];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName), HttpClientInterface::METHOD_PATCH, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->updateIndex($hashId, $indexName, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testGetIndex()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $body = [
            'name' => 'test name',
            'preset' => 'generic',
            'options' => ['exclude_out_of_stock_items' => true],
            'datasources' => [
                [
                    'type' => 'bigcommerce',
                    'options' => [
                        'access_token' => 'fake_access_token',
                        'store_hash' => 'fake_store_hash',
                        'url' => 'fake_url'
                    ]
                ]
            ],
        ];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName), HttpClientInterface::METHOD_GET, [], $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->getIndex($hashId, $indexName);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertInstanceOf(IndexModel::class, $response->getBody());

        /** @var IndexModel $index */
        $index = $response->getBody();
        $this->assertEquals($index->jsonSerialize(), $body);
    }

    public function testGetIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName), HttpClientInterface::METHOD_GET, [], $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->getIndex($hashId, $indexName);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testListIndex()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $body = [[
            'name' => 'test name',
            'preset' => 'generic',
            'options' => ['exclude_out_of_stock_items' => true],
            'datasources' => [
                [
                    'type' => 'bigcommerce',
                    'options' => [
                        'access_token' => 'fake_access_token',
                        'store_hash' => 'fake_store_hash',
                        'url' => 'fake_url'
                    ]
                ]
            ],
        ]];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId), HttpClientInterface::METHOD_GET, [], $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->listIndexes($hashId);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());

        /** @var array<IndexModel> $indexes */
        $indexes = $response->getBody();
        $this->assertCount(1, $indexes);;
        $this->assertInstanceOf(IndexModel::class, $indexes[0]);
        $this->assertEquals($indexes[0]->jsonSerialize(), $body[0]);
    }

    public function testDeleteIndex()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $response = HttpResponse::create(HttpStatusCode::NO_CONTENT);

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName), HttpClientInterface::METHOD_DELETE, [], $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->deleteIndex($hashId, $indexName);

        $this->assertSame(HttpStatusCode::NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName), HttpClientInterface::METHOD_DELETE, [], $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->deleteIndex($hashId, $indexName);
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