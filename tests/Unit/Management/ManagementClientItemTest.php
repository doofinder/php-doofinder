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

    public function testListItemNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->itemResource
            ->expects($this->once())
            ->method('listItems')
            ->with($hashId, $indexName)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->listItems($hashId, $indexName);
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

    public function testListItemSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody([$this->item]);

        $this->itemResource
            ->expects($this->once())
            ->method('listItems')
            ->with($hashId, $indexName)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->listItems($hashId, $indexName);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $items = $response->getBody();

        $this->assertCount(1, $items);
        $this->assertInstanceOf(ItemModel::class, $items[0]);

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
}