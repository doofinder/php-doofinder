<?php

namespace Tests\Unit\Management\Resources;

use Doofinder\Management\Resources\Item;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpResponse;
use Doofinder\Shared\HttpStatusCode;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Management\Model\Item as ItemModel;
use Doofinder\Shared\Interfaces\HttpResponseInterface;

class ItemTest extends BaseResourceTest
{
    private function createSut()
    {
        return Item::create($this->httpClient, $this->config);
    }

    private function getUrl($hashId, $indexName, $itemId = null, $isTemporalIndex = false)
    {
        $temporalIndex = $isTemporalIndex ? '/temp' : '';

        return self::BASE_URL . '/api/v2/search_engines/' . $hashId . '/indices/' . $indexName . $temporalIndex . '/items' . (!is_null($itemId)? '/' . $itemId : '');
    }

    public function testCreateItemSuccess()
    {
        $body = [
            'best_price' =>  74748791.45018,
            'categories' =>  [
                'consectetur',
                'voluptate do adipisicing consectetur'
            ],
            'df_group_leader' =>  true,
            'df_manual_boost' =>  94755610.41909,
            'id' =>  'magna'
        ];

        $params = array_merge(
            $body,
            ['group_id' =>  'commodo enim dolore qui exercitation']
        );

        $body['df_grouping_id'] = 'commodo enim dolore qui exercitation';

        $response = HttpResponse::create(HttpStatusCode::CREATED, json_encode($body));

        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName), HttpClientInterface::METHOD_POST, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->createItem($hashId, $indexName, $params);

        $this->assertSame(HttpStatusCode::CREATED, $response->getStatusCode());
        $this->assertInstanceOf(ItemModel::class, $response->getBody());

        /** @var ItemModel $index */
        $index = $response->getBody();
        $this->assertEquals($index->jsonSerialize(), $body);
    }

    public function testCreateItemInvalidParams()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error" : {"code": "bad_params"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $params = [
            'best_price' =>  'fake_price',
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName), HttpClientInterface::METHOD_POST, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->createItem($hashId, $indexName,$params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('bad_params', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateItemErrorWithNoMessage()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error": {"code" : "Something went wrong"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $params = [];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName), HttpClientInterface::METHOD_POST, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->createItem($hashId, $indexName, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('Something went wrong', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testUpdateItem()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';


        $body = [
            'best_price' =>  74748791.45018,
            'categories' =>  [
                'consectetur',
                'voluptate do adipisicing consectetur'
            ],
            'df_group_leader' =>  true,
            'df_grouping_id' =>  'commodo enim dolore qui exercitation',
            'df_manual_boost' =>  94755610.41909
        ];

        $params = array_merge(
            $body,
            ['group_id' =>  'commodo enim dolore qui exercitation']
        );

        $body['df_grouping_id'] = 'commodo enim dolore qui exercitation';
        $body['id'] = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName, $itemId), HttpClientInterface::METHOD_PATCH, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->updateItem($hashId, $indexName, $itemId, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertInstanceOf(ItemModel::class, $response->getBody());

        /** @var ItemModel $item */
        $item = $response->getBody();
        $this->assertEquals($item->jsonSerialize(), $body);
    }

    public function testUpdateItemNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');
        $params = [];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName, $itemId), HttpClientInterface::METHOD_PATCH, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->updateItem($hashId, $indexName, $itemId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testGetItem()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $body = [
            'best_price' =>  74748791.45018,
            'categories' =>  [
                'consectetur',
                'voluptate do adipisicing consectetur'
            ],
            'df_group_leader' =>  true,
            'df_grouping_id' =>  'commodo enim dolore qui exercitation',
            'df_manual_boost' =>  94755610.41909,
            'id' =>  'magna'
        ];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName, $itemId), HttpClientInterface::METHOD_GET, [], $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->getItem($hashId, $indexName, $itemId);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertInstanceOf(ItemModel::class, $response->getBody());

        /** @var ItemModel $item */
        $item = $response->getBody();
        $this->assertEquals($item->jsonSerialize(), $body);
    }

    public function testGetItemNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName, $itemId), HttpClientInterface::METHOD_GET, [], $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->getItem($hashId, $indexName, $itemId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testScrollIndex()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $responseItems = [[
            'best_price' =>  74748791.45018,
            'categories' =>  [
                'consectetur',
                'voluptate do adipisicing consectetur'
            ],
            'df_group_leader' =>  true,
            'df_grouping_id' =>  'commodo enim dolore qui exercitation',
            'df_manual_boost' =>  94755610.41909,
            'id' =>  'magna'
        ]];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode(
            [
                'items' => $responseItems,
                'scroll_id' => 'fake_scroll_id',
                'total' => 10
            ]
        ));
        $params = [
            'scroll_id' => 'fake_scroll_id',
            'rpp' => 1,
            'group_id' => 'fake_scroll_id'
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName), HttpClientInterface::METHOD_GET, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->scrollIndex($hashId, $indexName, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());

        /** @var array<ItemModel> $items */
        $body = $response->getBody();
        $items = $body['items'];
        $this->assertCount(1, $items);;
        $this->assertInstanceOf(ItemModel::class, $items[0]);
        $this->assertEquals($items[0]->jsonSerialize(), $responseItems[0]);
    }

    public function testDeleteItem()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $response = HttpResponse::create(HttpStatusCode::NO_CONTENT);

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName, $itemId), HttpClientInterface::METHOD_DELETE, [], $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->deleteItem($hashId, $indexName, $itemId);

        $this->assertSame(HttpStatusCode::NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteItemNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName, $itemId), HttpClientInterface::METHOD_DELETE, [], $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->deleteItem($hashId, $indexName, $itemId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateItemInTemporalIndexSuccess()
    {
        $body = [
            'best_price' =>  74748791.45018,
            'categories' =>  [
                'consectetur',
                'voluptate do adipisicing consectetur'
            ],
            'df_group_leader' =>  true,
            'df_manual_boost' =>  94755610.41909,
            'id' =>  'magna'
        ];

        $params = array_merge(
            $body,
            ['group_id' =>  'commodo enim dolore qui exercitation']
        );

        $body['df_grouping_id'] = 'commodo enim dolore qui exercitation';

        $response = HttpResponse::create(HttpStatusCode::CREATED, json_encode($body));

        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName, null, true), HttpClientInterface::METHOD_POST, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->createItemInTemporalIndex($hashId, $indexName, $params);

        $this->assertSame(HttpStatusCode::CREATED, $response->getStatusCode());
        $this->assertInstanceOf(ItemModel::class, $response->getBody());

        /** @var ItemModel $index */
        $index = $response->getBody();
        $this->assertEquals($index->jsonSerialize(), $body);
    }

    public function testCreateItemIntemporalIndexInvalidParams()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error" : {"code": "bad_params"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $params = [
            'best_price' =>  'fake_price',
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName, null, true), HttpClientInterface::METHOD_POST, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->createItemInTemporalIndex($hashId, $indexName,$params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('bad_params', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateItemInTemporalIndexErrorWithNoMessage()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error": {"code" : "Something went wrong"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $params = [];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId, $indexName, null, true), HttpClientInterface::METHOD_POST, $params, $this->assertBearerCallback())
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->createItemInTemporalIndex($hashId, $indexName, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('Something went wrong', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }
}