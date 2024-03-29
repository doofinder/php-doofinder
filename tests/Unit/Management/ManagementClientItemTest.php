<?php

namespace Tests\Unit\Management;

use Doofinder\Management\Model\Item as ItemModel;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpResponse;
use Doofinder\Shared\HttpStatusCode;

class ManagementClientItemTest extends BaseManagementClientTest
{
    /**
     * @var ItemModel
     */
    private $item;

    /**
     * @var array
     */
    private $itemParams;

    public function setUp()
    {
        parent::setUp();

        $this->itemParams = [
            'best_price' =>  74748791.4501,
            'categories' =>  [
                'consectetur',
                'voluptate do adipisicing consectetur'
            ],
            'df_group_leader' =>  true,
            'df_grouping_id' =>  'commodo enim dolore qui exercitation',
            'df_manual_boost' =>  94755610.4190,
            'id' =>  'magna',
        ];

        $this->item = ItemModel::createFromArray(
            $this->itemParams
        );

        $this->itemParams['group_id'] =  'commodo enim dolore qui exercitation';
        unset($this->itemParams['id']);
        unset($this->itemParams['df_grouping_id']);
    }

    public function testCreateItemSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $httpResponse = HttpResponse::create(HttpStatusCode::CREATED);
        $httpResponse->setBody($this->item);

        $this->itemResource
            ->expects($this->once())
            ->method('createItem')
            ->with($hashId, $indexName, $this->itemParams)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->createItem($hashId, $indexName, $this->itemParams);

        $this->assertSame(HttpStatusCode::CREATED, $response->getStatusCode());
        $item = $response->getBody();

        $this->assertInstanceOf(ItemModel::class, $item);

        $this->assertSame($this->item->getId(), $item->getId());
        $this->assertSame($this->item->getDfGroupingId(), $item->getDfGroupingId());
        $this->assertSame($this->item->getDfGroupLeader(), $item->getDfGroupLeader());
        $this->assertSame($this->item->getDfManualBoost(), $item->getDfManualBoost());
        $this->assertSame($this->item->getCategories(), $item->getCategories());
        $this->assertSame($this->item->getBestPrice(), $item->getBestPrice());
        $this->assertSame($this->item->getAdditionalFields(), $item->getAdditionalFields());
    }

    public function testCreateItemNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('createItem')
            ->with($hashId, $indexName, $this->itemParams)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createItem($hashId, $indexName, $this->itemParams);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::UNAUTHORIZED, $e->getCode());
            $this->assertSame('The user hasn\'t provided valid authorization.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateItemInvalidParams()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('createItem')
            ->with($hashId, $indexName, [])
            ->willThrowException($this->badParametersException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createItem($hashId, $indexName, []);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            $this->assertSame('Request contains wrong parameter or values.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateItemNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('createItem')
            ->with($hashId, $indexName, $this->itemParams)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createItem($hashId, $indexName, $this->itemParams);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testUpdateItemNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $this->itemResource
            ->expects($this->once())
            ->method('updateItem')
            ->with($hashId, $indexName, $itemId, $this->itemParams)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateItem($hashId, $indexName, $itemId, $this->itemParams);
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

    public function testUpdateItemInvalidParams()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';
        $params = [];

        $this->itemResource
            ->expects($this->once())
            ->method('updateItem')
            ->with($hashId, $indexName, $itemId, $params)
            ->willThrowException($this->badParametersException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateItem($hashId, $indexName, $itemId, $params);
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

    public function testUpdateItemNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $this->itemResource
            ->expects($this->once())
            ->method('updateItem')
            ->with($hashId, $indexName, $itemId, $this->itemParams)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateItem($hashId, $indexName, $itemId, $this->itemParams);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testUpdateItemSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $managementClient = $this->createSut();

        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody($this->item);

        $this->itemResource
            ->expects($this->once())
            ->method('updateItem')
            ->with($hashId, $indexName, $itemId, $this->itemParams)
            ->willReturn($httpResponse);

        $response = $managementClient->updateItem($hashId, $indexName, $itemId, $this->itemParams);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        /** @var ItemModel $item */
        $item = $response->getBody();

        $this->assertInstanceOf(ItemModel::class, $item);

        $this->assertSame($this->item->getId(), $item->getId());
        $this->assertSame($this->item->getDfGroupingId(), $item->getDfGroupingId());
        $this->assertSame($this->item->getDfGroupLeader(), $item->getDfGroupLeader());
        $this->assertSame($this->item->getDfManualBoost(), $item->getDfManualBoost());
        $this->assertSame($this->item->getCategories(), $item->getCategories());
        $this->assertSame($this->item->getBestPrice(), $item->getBestPrice());
        $this->assertSame($this->item->getAdditionalFields(), $item->getAdditionalFields());
    }

    public function testGetItemNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $this->itemResource
            ->expects($this->once())
            ->method('getItem')
            ->with($hashId, $indexName, $itemId)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->getItem($hashId, $indexName, $itemId);
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

    public function testGetItemNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $this->itemResource
            ->expects($this->once())
            ->method('getItem')
            ->with($hashId, $indexName, $itemId)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->getItem($hashId, $indexName, $itemId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testGetItemSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';
        
        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody($this->item);

        $this->itemResource
            ->expects($this->once())
            ->method('getItem')
            ->with($hashId, $indexName, $itemId)
            ->willReturn($httpResponse);
        $managementClient = $this->createSut();

        $response = $managementClient->getItem($hashId, $indexName, $itemId);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $item = $response->getBody();

        $this->assertInstanceOf(ItemModel::class, $item);

        $this->assertSame($this->item->getId(), $item->getId());
        $this->assertSame($this->item->getDfGroupingId(), $item->getDfGroupingId());
        $this->assertSame($this->item->getDfGroupLeader(), $item->getDfGroupLeader());
        $this->assertSame($this->item->getDfManualBoost(), $item->getDfManualBoost());
        $this->assertSame($this->item->getCategories(), $item->getCategories());
        $this->assertSame($this->item->getBestPrice(), $item->getBestPrice());
        $this->assertSame($this->item->getAdditionalFields(), $item->getAdditionalFields());
    }

    public function testScrollIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('scrollIndex')
            ->with($hashId, $indexName)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->scrollIndex($hashId, $indexName);
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

    public function testScrollIndexSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody([
            'items' => [$this->item],
            'scroll_id' => 1234,
            'total' => 1,
        ]);

        $this->itemResource
            ->expects($this->once())
            ->method('scrollIndex')
            ->with($hashId, $indexName)
            ->willReturn($httpResponse);

        $params = [
            'scroll_id' => 'fake_scroll_id',
            'rpp' => 1,
            'group_id' => 'fake_scroll_id'
        ];

        $managementClient = $this->createSut();
        $response = $managementClient->scrollIndex($hashId, $indexName, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();

        $items = $body['items'];
        $this->assertCount(1, $items);
        $this->assertInstanceOf(ItemModel::class, $items[0]);

        $this->assertSame(1234, $body['scroll_id']);
        $this->assertSame(1, $body['total']);

        $item = $items[0];
        $this->assertSame($this->item->getId(), $item->getId());
        $this->assertSame($this->item->getDfGroupingId(), $item->getDfGroupingId());
        $this->assertSame($this->item->getDfGroupLeader(), $item->getDfGroupLeader());
        $this->assertSame($this->item->getDfManualBoost(), $item->getDfManualBoost());
        $this->assertSame($this->item->getCategories(), $item->getCategories());
        $this->assertSame($this->item->getBestPrice(), $item->getBestPrice());
        $this->assertSame($this->item->getAdditionalFields(), $item->getAdditionalFields());
    }

    public function testDeleteItemNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $this->itemResource
            ->expects($this->once())
            ->method('deleteItem')
            ->with($hashId, $indexName, $itemId)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->deleteItem($hashId, $indexName, $itemId);
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

    public function testDeleteItemNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $this->itemResource
            ->expects($this->once())
            ->method('deleteItem')
            ->with($hashId, $indexName, $itemId)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->deleteItem($hashId, $indexName, $itemId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testDeleteItemSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $httpResponse = HttpResponse::create(HttpStatusCode::NO_CONTENT);

        $this->itemResource
            ->expects($this->once())
            ->method('deleteItem')
            ->with($hashId, $indexName, $itemId)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();

        $response = $managementClient->deleteItem($hashId, $indexName, $itemId);

        $this->assertSame(HttpStatusCode::NO_CONTENT, $response->getStatusCode());
    }

    public function testCreateItemInTemporalIndexSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $httpResponse = HttpResponse::create(HttpStatusCode::CREATED);
        $httpResponse->setBody($this->item);

        $this->itemResource
            ->expects($this->once())
            ->method('createItemInTemporalIndex')
            ->with($hashId, $indexName, $this->itemParams)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->createItemInTemporalIndex($hashId, $indexName, $this->itemParams);

        $this->assertSame(HttpStatusCode::CREATED, $response->getStatusCode());
        $item = $response->getBody();

        $this->assertInstanceOf(ItemModel::class, $item);

        $this->assertSame($this->item->getId(), $item->getId());
        $this->assertSame($this->item->getDfGroupingId(), $item->getDfGroupingId());
        $this->assertSame($this->item->getDfGroupLeader(), $item->getDfGroupLeader());
        $this->assertSame($this->item->getDfManualBoost(), $item->getDfManualBoost());
        $this->assertSame($this->item->getCategories(), $item->getCategories());
        $this->assertSame($this->item->getBestPrice(), $item->getBestPrice());
        $this->assertSame($this->item->getAdditionalFields(), $item->getAdditionalFields());
    }

    public function testCreateItemInTemporalIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('createItemInTemporalIndex')
            ->with($hashId, $indexName, $this->itemParams)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createItemInTemporalIndex($hashId, $indexName, $this->itemParams);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::UNAUTHORIZED, $e->getCode());
            $this->assertSame('The user hasn\'t provided valid authorization.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateItemInTemporalIndexInvalidParams()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('createItemInTemporalIndex')
            ->with($hashId, $indexName, [])
            ->willThrowException($this->badParametersException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createItemInTemporalIndex($hashId, $indexName, []);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            $this->assertSame('Request contains wrong parameter or values.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateItemInTemporalIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('createItemInTemporalIndex')
            ->with($hashId, $indexName, $this->itemParams)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createItemInTemporalIndex($hashId, $indexName, $this->itemParams);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testUpdateItemInTemporalIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $this->itemResource
            ->expects($this->once())
            ->method('updateItemInTemporalIndex')
            ->with($hashId, $indexName, $itemId, $this->itemParams)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateItemInTemporalIndex($hashId, $indexName, $itemId, $this->itemParams);
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

    public function testUpdateItemInTemporalIndexInvalidParams()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';
        $params = [];

        $this->itemResource
            ->expects($this->once())
            ->method('updateItemInTemporalIndex')
            ->with($hashId, $indexName, $itemId, $params)
            ->willThrowException($this->badParametersException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateItemInTemporalIndex($hashId, $indexName, $itemId, $params);
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

    public function testUpdateItemInTemporalIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $this->itemResource
            ->expects($this->once())
            ->method('updateItemInTemporalIndex')
            ->with($hashId, $indexName, $itemId, $this->itemParams)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateItemInTemporalIndex($hashId, $indexName, $itemId, $this->itemParams);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testUpdateItemInTemporalIndexSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $managementClient = $this->createSut();

        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody($this->item);

        $this->itemResource
            ->expects($this->once())
            ->method('updateItemInTemporalIndex')
            ->with($hashId, $indexName, $itemId, $this->itemParams)
            ->willReturn($httpResponse);

        $response = $managementClient->updateItemInTemporalIndex($hashId, $indexName, $itemId, $this->itemParams);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        /** @var ItemModel $item */
        $item = $response->getBody();

        $this->assertInstanceOf(ItemModel::class, $item);

        $this->assertSame($this->item->getId(), $item->getId());
        $this->assertSame($this->item->getDfGroupingId(), $item->getDfGroupingId());
        $this->assertSame($this->item->getDfGroupLeader(), $item->getDfGroupLeader());
        $this->assertSame($this->item->getDfManualBoost(), $item->getDfManualBoost());
        $this->assertSame($this->item->getCategories(), $item->getCategories());
        $this->assertSame($this->item->getBestPrice(), $item->getBestPrice());
        $this->assertSame($this->item->getAdditionalFields(), $item->getAdditionalFields());
    }

    public function testGetItemFromTemporalIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $this->itemResource
            ->expects($this->once())
            ->method('getItemFromTemporalIndex')
            ->with($hashId, $indexName, $itemId)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->getItemFromTemporalIndex($hashId, $indexName, $itemId);
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

    public function testGetItemFromTemporalIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $this->itemResource
            ->expects($this->once())
            ->method('getItemFromTemporalIndex')
            ->with($hashId, $indexName, $itemId)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->getItemFromTemporalIndex($hashId, $indexName, $itemId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testGetItemFromTemporalIndexSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody($this->item);

        $this->itemResource
            ->expects($this->once())
            ->method('getItemFromTemporalIndex')
            ->with($hashId, $indexName, $itemId)
            ->willReturn($httpResponse);
        $managementClient = $this->createSut();

        $response = $managementClient->getItemFromTemporalIndex($hashId, $indexName, $itemId);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $item = $response->getBody();

        $this->assertInstanceOf(ItemModel::class, $item);

        $this->assertSame($this->item->getId(), $item->getId());
        $this->assertSame($this->item->getDfGroupingId(), $item->getDfGroupingId());
        $this->assertSame($this->item->getDfGroupLeader(), $item->getDfGroupLeader());
        $this->assertSame($this->item->getDfManualBoost(), $item->getDfManualBoost());
        $this->assertSame($this->item->getCategories(), $item->getCategories());
        $this->assertSame($this->item->getBestPrice(), $item->getBestPrice());
        $this->assertSame($this->item->getAdditionalFields(), $item->getAdditionalFields());
    }

    public function testDeleteItemFromTemporalIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $this->itemResource
            ->expects($this->once())
            ->method('deleteItemFromTemporalIndex')
            ->with($hashId, $indexName, $itemId)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->deleteItemFromTemporalIndex($hashId, $indexName, $itemId);
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

    public function testDeleteItemFromTemporalIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $this->itemResource
            ->expects($this->once())
            ->method('deleteItemFromTemporalIndex')
            ->with($hashId, $indexName, $itemId)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->deleteItemFromTemporalIndex($hashId, $indexName, $itemId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testDeleteItemFromTemporalIndexSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $itemId = '5a11c448-bd14-4a78-972a-28070ce6db7d';

        $httpResponse = HttpResponse::create(HttpStatusCode::NO_CONTENT);

        $this->itemResource
            ->expects($this->once())
            ->method('deleteItemFromTemporalIndex')
            ->with($hashId, $indexName, $itemId)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();

        $response = $managementClient->deleteItemFromTemporalIndex($hashId, $indexName, $itemId);

        $this->assertSame(HttpStatusCode::NO_CONTENT, $response->getStatusCode());
    }

    public function testFindItemsFromTemporalIndexSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody([
            [
                'id' => 'test',
                'found' => true,
                'item' => $this->item,
            ],
            [
                'id' => 'fake',
                'found' => false,
                'item' => [],
            ],
        ]);

        $params = [
            ['id' => 'test'],
            ['id' => 'fake'],
        ];

        $this->itemResource
            ->expects($this->once())
            ->method('findItemsFromTemporalIndex')
            ->with($hashId, $indexName, $params)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->findItemsFromTemporalIndex($hashId, $indexName, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();

        $this->assertCount(2, $body);
        $this->assertSame('test', $body[0]['id']);
        $this->assertTrue($body[0]['found']);
        $this->assertInstanceOf(ItemModel::class, $body[0]['item']);

        $this->assertSame('fake', $body[1]['id']);
        $this->assertFalse($body[1]['found']);
        $this->assertEmpty($body[1]['item']);
    }

    public function testFindItemsFromTemporalIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('findItemsFromTemporalIndex')
            ->with($hashId, $indexName, [])
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->findItemsFromTemporalIndex($hashId, $indexName, []);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testFindItemsSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody([
            [
                'id' => 'test',
                'found' => true,
                'item' => $this->item,
            ],
            [
                'id' => 'fake',
                'found' => false,
                'item' => [],
            ],
        ]);

        $params = [
            ['id' => 'test'],
            ['id' => 'fake'],
        ];

        $this->itemResource
            ->expects($this->once())
            ->method('findItems')
            ->with($hashId, $indexName, $params)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->findItems($hashId, $indexName, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();

        $this->assertCount(2, $body);
        $this->assertSame('test', $body[0]['id']);
        $this->assertTrue($body[0]['found']);
        $this->assertInstanceOf(ItemModel::class, $body[0]['item']);

        $this->assertSame('fake', $body[1]['id']);
        $this->assertFalse($body[1]['found']);
        $this->assertEmpty($body[1]['item']);
    }

    public function testFindItemsNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $params = [['id' => 'fake_id']];
        $this->itemResource
            ->expects($this->once())
            ->method('findItems')
            ->with($hashId, $indexName, $params)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->findItems($hashId, $indexName, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testCountItemsSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode(['count' => 5]));

        $this->itemResource
            ->expects($this->once())
            ->method('countItems')
            ->with($hashId, $indexName)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->countItems($hashId, $indexName);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();

        $this->assertArrayHasKey('count', $body);
        $this->assertSame(5, $body['count']);
    }

    public function testCountItemsNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('countItems')
            ->with($hashId, $indexName)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->countItems($hashId, $indexName);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testCountItemsNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('countItems')
            ->with($hashId, $indexName)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->countItems($hashId, $indexName);
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

    public function testCreateItemsInBulkInTemporalIndexSuccess()
    {
        $hashId = 'ab732549b8b738f2a86f8efd9eaf4bcb';
        $indexName = 'test';

        $body = [
            'errors' => false,
            'results' => [
                [
                    'id' => 'magna',
                    'result' => 'created'
                ]
            ]
        ];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->itemResource
            ->expects($this->once())
            ->method('createItemsInBulkInTemporalIndex')
            ->with($hashId, $indexName, [$this->itemParams])
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->createItemsInBulkInTemporalIndex($hashId, $indexName, [$this->itemParams]);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();

        $this->assertArrayHasKey('errors', $body);
        $this->assertArrayHasKey('results', $body);
        $this->assertCount(1, $body['results']);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();
        $this->assertArrayHasKey('errors', $body);
        $this->assertArrayHasKey('results', $body);
        $this->assertCount(1, $body['results']);

        $this->assertEquals($body['results'][0], ['id' => 'magna', 'result' => 'created']);
        $this->assertSame($body['errors'], false);
    }

    public function testCreateItemsInBulkInTemporalIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('createItemsInBulkInTemporalIndex')
            ->with($hashId, $indexName, [$this->itemParams])
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createItemsInBulkInTemporalIndex($hashId, $indexName, [$this->itemParams]);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateItemsInBulkInTemporalIndexInvalidParams()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('createItemsInBulkInTemporalIndex')
            ->with($hashId, $indexName, [[]])
            ->willThrowException($this->badParametersException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createItemsInBulkInTemporalIndex($hashId, $indexName, [[]]);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            $this->assertSame('Request contains wrong parameter or values.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateItemsInBulkInTemporalIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('createItemsInBulkInTemporalIndex')
            ->with($hashId, $indexName, [$this->itemParams])
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createItemsInBulkInTemporalIndex($hashId, $indexName, [$this->itemParams]);
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

    public function testUpdateItemsInBulkInTemporalIndexSuccess()
    {
        $hashId = 'ab732549b8b738f2a86f8efd9eaf4bcb';
        $indexName = 'test';

        $body = [
            'errors' => false,
            'results' => [
                [
                    'id' => 'magna',
                    'result' => 'updated'
                ]
            ]
        ];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->itemResource
            ->expects($this->once())
            ->method('updateItemsInBulkInTemporalIndex')
            ->with($hashId, $indexName, [$this->itemParams])
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->updateItemsInBulkInTemporalIndex($hashId, $indexName, [$this->itemParams]);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();

        $this->assertArrayHasKey('errors', $body);
        $this->assertArrayHasKey('results', $body);
        $this->assertCount(1, $body['results']);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();
        $this->assertArrayHasKey('errors', $body);
        $this->assertArrayHasKey('results', $body);
        $this->assertCount(1, $body['results']);

        $this->assertEquals($body['results'][0], ['id' => 'magna', 'result' => 'updated']);
        $this->assertSame($body['errors'], false);
    }

    public function testUpdateItemsInBulkInTemporalIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('updateItemsInBulkInTemporalIndex')
            ->with($hashId, $indexName, [$this->itemParams])
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateItemsInBulkInTemporalIndex($hashId, $indexName, [$this->itemParams]);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testUpdateItemsInBulkInTemporalIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('updateItemsInBulkInTemporalIndex')
            ->with($hashId, $indexName, [$this->itemParams])
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateItemsInBulkInTemporalIndex($hashId, $indexName, [$this->itemParams]);
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

    public function testDeleteItemsInBulkInTemporalIndexSuccess()
    {
        $hashId = 'ab732549b8b738f2a86f8efd9eaf4bcb';
        $indexName = 'test';
        $params = [
            [
                'id' => 'magna'
            ]
        ];

        $body = [
            'errors' => false,
            'results' => [
                [
                    'id' => 'magna',
                    'result' => 'deleted'
                ]
            ]
        ];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->itemResource
            ->expects($this->once())
            ->method('deleteItemsInBulkInTemporalIndex')
            ->with($hashId, $indexName, $params)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->deleteItemsInBulkInTemporalIndex($hashId, $indexName, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();

        $this->assertArrayHasKey('errors', $body);
        $this->assertArrayHasKey('results', $body);
        $this->assertCount(1, $body['results']);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();
        $this->assertArrayHasKey('errors', $body);
        $this->assertArrayHasKey('results', $body);
        $this->assertCount(1, $body['results']);

        $this->assertEquals($body['results'][0], ['id' => 'magna', 'result' => 'deleted']);
        $this->assertSame($body['errors'], false);
    }

    public function testDeleteItemsInBulkInTemporalIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $params = [
            [
                'id' => 'magna'
            ]
        ];

        $this->itemResource
            ->expects($this->once())
            ->method('deleteItemsInBulkInTemporalIndex')
            ->with($hashId, $indexName, $params)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->deleteItemsInBulkInTemporalIndex($hashId, $indexName, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testDeleteItemsInBulkInTemporalIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $params = [
            [
                'id' => 'magna'
            ]
        ];

        $this->itemResource
            ->expects($this->once())
            ->method('deleteItemsInBulkInTemporalIndex')
            ->with($hashId, $indexName, $params)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->deleteItemsInBulkInTemporalIndex($hashId, $indexName, $params);
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

    public function testCreateItemsInBulkSuccess()
    {
        $hashId = 'ab732549b8b738f2a86f8efd9eaf4bcb';
        $indexName = 'test';

        $body = [
            'errors' => false,
            'results' => [
                [
                    'id' => 'magna',
                    'result' => 'created'
                ]
            ]
        ];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->itemResource
            ->expects($this->once())
            ->method('createItemsInBulk')
            ->with($hashId, $indexName, [$this->itemParams])
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->createItemsInBulk($hashId, $indexName, [$this->itemParams]);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();

        $this->assertArrayHasKey('errors', $body);
        $this->assertArrayHasKey('results', $body);
        $this->assertCount(1, $body['results']);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();
        $this->assertArrayHasKey('errors', $body);
        $this->assertArrayHasKey('results', $body);
        $this->assertCount(1, $body['results']);

        $this->assertEquals($body['results'][0], ['id' => 'magna', 'result' => 'created']);
        $this->assertSame($body['errors'], false);
    }

    public function testCreateItemsInBulkNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('createItemsInBulk')
            ->with($hashId, $indexName, [$this->itemParams])
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createItemsInBulk($hashId, $indexName, [$this->itemParams]);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateItemsInBulkInvalidParams()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('createItemsInBulk')
            ->with($hashId, $indexName, [[]])
            ->willThrowException($this->badParametersException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createItemsInBulk($hashId, $indexName, [[]]);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            $this->assertSame('Request contains wrong parameter or values.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateItemsInBulkNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('createItemsInBulk')
            ->with($hashId, $indexName, [$this->itemParams])
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createItemsInBulk($hashId, $indexName, [$this->itemParams]);
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

    public function testUpdateItemsInBulkSuccess()
    {
        $hashId = 'ab732549b8b738f2a86f8efd9eaf4bcb';
        $indexName = 'test';

        $body = [
            'errors' => false,
            'results' => [
                [
                    'id' => 'magna',
                    'result' => 'updated'
                ]
            ]
        ];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->itemResource
            ->expects($this->once())
            ->method('updateItemsInBulk')
            ->with($hashId, $indexName, [$this->itemParams])
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->updateItemsInBulk($hashId, $indexName, [$this->itemParams]);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();

        $this->assertArrayHasKey('errors', $body);
        $this->assertArrayHasKey('results', $body);
        $this->assertCount(1, $body['results']);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();
        $this->assertArrayHasKey('errors', $body);
        $this->assertArrayHasKey('results', $body);
        $this->assertCount(1, $body['results']);

        $this->assertEquals($body['results'][0], ['id' => 'magna', 'result' => 'updated']);
        $this->assertSame($body['errors'], false);
    }

    public function testUpdateItemsInBulkNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('updateItemsInBulk')
            ->with($hashId, $indexName, [$this->itemParams])
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateItemsInBulk($hashId, $indexName, [$this->itemParams]);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testUpdateItemsInBulkNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('updateItemsInBulk')
            ->with($hashId, $indexName, [$this->itemParams])
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateItemsInBulk($hashId, $indexName, [$this->itemParams]);
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

    public function testDeleteItemsInBulkSuccess()
    {
        $hashId = 'ab732549b8b738f2a86f8efd9eaf4bcb';
        $indexName = 'test';
        $params = [
            [
                'id' => 'magna'
            ]
        ];

        $body = [
            'errors' => false,
            'results' => [
                [
                    'id' => 'magna',
                    'result' => 'deleted'
                ]
            ]
        ];

        $httpResponse = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $this->itemResource
            ->expects($this->once())
            ->method('deleteItemsInBulk')
            ->with($hashId, $indexName, $params)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->deleteItemsInBulk($hashId, $indexName, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();

        $this->assertArrayHasKey('errors', $body);
        $this->assertArrayHasKey('results', $body);
        $this->assertCount(1, $body['results']);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $body = $response->getBody();
        $this->assertArrayHasKey('errors', $body);
        $this->assertArrayHasKey('results', $body);
        $this->assertCount(1, $body['results']);

        $this->assertEquals($body['results'][0], ['id' => 'magna', 'result' => 'deleted']);
        $this->assertSame($body['errors'], false);
    }

    public function testDeleteItemsInBulkNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $params = [
            [
                'id' => 'magna'
            ]
        ];

        $this->itemResource
            ->expects($this->once())
            ->method('deleteItemsInBulk')
            ->with($hashId, $indexName, $params)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->deleteItemsInBulk($hashId, $indexName, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testDeleteItemsInBulkNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $params = [
            [
                'id' => 'magna'
            ]
        ];

        $this->itemResource
            ->expects($this->once())
            ->method('deleteItemsInBulk')
            ->with($hashId, $indexName, $params)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->deleteItemsInBulk($hashId, $indexName, $params);
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
}