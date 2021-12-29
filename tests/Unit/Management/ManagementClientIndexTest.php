<?php

namespace Tests\Unit\Management;

use Doofinder\Management\Model\Index as IndexModel;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpResponse;
use Doofinder\Shared\HttpStatusCode;

class ManagementClientIndexTest extends BaseManagementClientTest
{   /**
     * @var IndexModel
     */
    private $index;

    public function setUp()
    {
        parent::setUp();

        $this->index = IndexModel::createFromArray(
            [
                'datasources' => [
                    [
                        'options' => [
                            'url' => 'fake_url',
                            'page_size' => 100
                        ],
                        'type' => 'file'
                    ]
                ],
                'name' => 'product_4',
                'options' => [
                    'exclude_out_of_stock_items' => false
                ],
                'preset' => 'product'
            ]
        );

    }

    public function testCreateIndexSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $params = [
            'datasources' => [
                [
                    'options' => [
                        'url' => 'fake_url',
                        'page_size' => 100
                    ],
                    'type' => 'file'
                ]
            ],
            'name' => 'product_4',
            'options' => [
                    'exclude_out_of_stock_items' => false
            ],
            'preset' => 'product'
        ];

        $httpResponse = HttpResponse::create(HttpStatusCode::CREATED);
        $httpResponse->setBody($this->index);

        $this->indexResource
            ->expects($this->once())
            ->method('createIndex')
            ->with($hashId, $params)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->createIndex($hashId, $params);

        $this->assertSame(HttpStatusCode::CREATED, $response->getStatusCode());
        $index = $response->getBody();

        $this->assertInstanceOf(IndexModel::class, $index);

        $this->assertSame($this->index->getName(), $index->getName());
        $this->assertSame($this->index->getPreset(), $index->getPreset());
        $this->assertSame($this->index->getOptions(), $index->getOptions());
        $this->assertEquals($this->index->getDataSources(), $index->getDataSources());
    }

    public function testCreateIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $params = [
            'datasources' => [
                [
                    'options' => [
                        'url' => 'fake_url',
                        'page_size' => 100
                    ],
                    'type' => 'file'
                ]
            ],
            'name' => 'product_4',
            'options' => [
                'exclude_out_of_stock_items' => false
            ],
            'preset' => 'product'
        ];

        $this->indexResource
            ->expects($this->once())
            ->method('createIndex')
            ->with($hashId, $params)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createIndex($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::UNAUTHORIZED, $e->getCode());
            $this->assertSame('The user hasn\'t provided valid authorization.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateIndexInvalidParams()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $this->indexResource
            ->expects($this->once())
            ->method('createIndex')
            ->with($hashId, [])
            ->willThrowException($this->badParametersException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createIndex($hashId, []);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            $this->assertSame('Request contains wrong parameter or values.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $params = [
            'datasources' => [
                [
                    'options' => [
                        'url' => 'fake_url',
                        'page_size' => 100
                    ],
                    'type' => 'file'
                ]
            ],
            'name' => 'product_4',
            'options' => [
                'exclude_out_of_stock_items' => false
            ],
            'preset' => 'product'
        ];

        $this->indexResource
            ->expects($this->once())
            ->method('createIndex')
            ->with($hashId, $params)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createIndex($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testUpdateIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $params = [
            'datasources' => [
                [
                    'options' => [
                        'url' => 'fake_url',
                        'page_size' => 100
                    ],
                    'type' => 'file'
                ]
            ],
            'options' => [
                'exclude_out_of_stock_items' => false
            ]
        ];

        $this->indexResource
            ->expects($this->once())
            ->method('updateIndex')
            ->with($hashId, $indexName, $params)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateIndex($hashId, $indexName, $params);
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

    public function testUpdateIndexInvalidParams()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        $params = [];

        $this->indexResource
            ->expects($this->once())
            ->method('updateIndex')
            ->with($hashId, $indexName, $params)
            ->willThrowException($this->badParametersException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateIndex($hashId, $indexName, $params);
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

    public function testUpdateIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $params = [
            'datasources' => [
                [
                    'options' => [
                        'url' => 'fake_url',
                        'page_size' => 100
                    ],
                    'type' => 'file'
                ]
            ],
            'options' => [
                'exclude_out_of_stock_items' => false
            ]
        ];

        $this->indexResource
            ->expects($this->once())
            ->method('updateIndex')
            ->with($hashId, $indexName, $params)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->updateIndex($hashId, $indexName, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testUpdateIndexSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $managementClient = $this->createSut();
        $params = [
            'datasources' => [
                [
                    'options' => [
                        'url' => 'fake_url',
                        'page_size' => 100
                    ],
                    'type' => 'file'
                ]
            ],
            'options' => [
                'exclude_out_of_stock_items' => false
            ]
        ];


        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody($this->index);

        $this->indexResource
            ->expects($this->once())
            ->method('updateIndex')
            ->with($hashId, $indexName, $params)
            ->willReturn($httpResponse);

        $response = $managementClient->updateIndex($hashId, $indexName, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        /** @var IndexModel $index */
        $index = $response->getBody();

        $this->assertInstanceOf(IndexModel::class, $index);

        $this->assertSame($this->index->getName(), $index->getName());
        $this->assertSame($this->index->getPreset(), $index->getPreset());
        $this->assertSame($this->index->getOptions(), $index->getOptions());
        $this->assertEquals($this->index->getDataSources(), $index->getDataSources());
    }

    public function testGetIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        
        $this->indexResource
            ->expects($this->once())
            ->method('getIndex')
            ->with($hashId, $indexName)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->getIndex($hashId, $indexName);
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

    public function testGetIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        
        $this->indexResource
            ->expects($this->once())
            ->method('getIndex')
            ->with($hashId, $indexName)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->getIndex($hashId, $indexName);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testGetIndexSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';
        
        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody($this->index);

        $this->indexResource
            ->expects($this->once())
            ->method('getIndex')
            ->with($hashId, $indexName)
            ->willReturn($httpResponse);
        $managementClient = $this->createSut();

        $response = $managementClient->getIndex($hashId, $indexName);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $index = $response->getBody();

        $this->assertInstanceOf(IndexModel::class, $index);

        $this->assertSame($this->index->getName(), $index->getName());
        $this->assertSame($this->index->getPreset(), $index->getPreset());
        $this->assertSame($this->index->getOptions(), $index->getOptions());
        $this->assertEquals($this->index->getDataSources(), $index->getDataSources());
    }

    public function testListIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $this->indexResource
            ->expects($this->once())
            ->method('listIndexes')
            ->with($hashId)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->listIndexes($hashId);
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

    public function testListIndexSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        
        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody([$this->index]);

        $this->indexResource
            ->expects($this->once())
            ->method('listIndexes')
            ->with($hashId)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->listIndexes($hashId);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $indexes = $response->getBody();

        $this->assertCount(1, $indexes);
        $this->assertInstanceOf(IndexModel::class, $indexes[0]);

        $index = $indexes[0];
        $this->assertSame($this->index->getName(), $index->getName());
        $this->assertSame($this->index->getPreset(), $index->getPreset());
        $this->assertSame($this->index->getOptions(), $index->getOptions());
        $this->assertEquals($this->index->getDataSources(), $index->getDataSources());
    }

    public function testDeleteIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->indexResource
            ->expects($this->once())
            ->method('deleteIndex')
            ->with($hashId, $indexName)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->deleteIndex($hashId, $indexName);
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

    public function testDeleteIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->indexResource
            ->expects($this->once())
            ->method('deleteIndex')
            ->with($hashId, $indexName)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->deleteIndex($hashId, $indexName);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());

            $previousMessage = json_decode($e->getPrevious()->getMessage(), true)['error'];
            $this->assertSame('not_found', $previousMessage['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testDeleteIndexSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $httpResponse = HttpResponse::create(HttpStatusCode::NO_CONTENT);

        $this->indexResource
            ->expects($this->once())
            ->method('deleteIndex')
            ->with($hashId, $indexName)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();

        $response = $managementClient->deleteIndex($hashId, $indexName);

        $this->assertSame(HttpStatusCode::NO_CONTENT, $response->getStatusCode());
    }

    public function testCreateTemporaryIndexSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $httpResponse = HttpResponse::create(HttpStatusCode::CREATED);
        $httpResponse->setBody(['status' => 'OK']);

        $this->indexResource
            ->expects($this->once())
            ->method('createTemporaryIndex')
            ->with($hashId, $indexName)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->createTemporaryIndex($hashId, $indexName);

        $this->assertSame(HttpStatusCode::CREATED, $response->getStatusCode());
        $this->assertSame(['status' => 'OK'], $response->getBody());
    }

    public function testCreateTemporaryIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->indexResource
            ->expects($this->once())
            ->method('createTemporaryIndex')
            ->with($hashId, $indexName)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createTemporaryIndex($hashId, $indexName);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::UNAUTHORIZED, $e->getCode());
            $this->assertSame('The user hasn\'t provided valid authorization.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateTemporaryIndexAlreadyCreated()
    {
        $hashId = 'ee859baa0f1d2d6abb7611046f297148';
        $indexName = 'product_4';
        $conflictException = new ApiException(
            '{"error":{"code":"too_many_temporary","message":"Too many temporary indices"}}',
            HttpStatusCode::CONFLICT
        );

        $this->indexResource
            ->expects($this->once())
            ->method('createTemporaryIndex')
            ->with($hashId, $indexName)
            ->willThrowException($conflictException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createTemporaryIndex($hashId, $indexName);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::CONFLICT, $e->getCode());
            $this->assertSame('There are too many temporary index.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testCreateTemporaryIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->indexResource
            ->expects($this->once())
            ->method('createTemporaryIndex')
            ->with($hashId, $indexName)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->createTemporaryIndex($hashId, $indexName);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testDeleteTemporaryIndexSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $httpResponse = HttpResponse::create(HttpStatusCode::NO_CONTENT);

        $this->indexResource
            ->expects($this->once())
            ->method('deleteTemporaryIndex')
            ->with($hashId, $indexName)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->deleteTemporaryIndex($hashId, $indexName);

        $this->assertSame(HttpStatusCode::NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteTemporaryIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->indexResource
            ->expects($this->once())
            ->method('deleteTemporaryIndex')
            ->with($hashId, $indexName)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->deleteTemporaryIndex($hashId, $indexName);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::UNAUTHORIZED, $e->getCode());
            $this->assertSame('The user hasn\'t provided valid authorization.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testDeleteTemporaryIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->indexResource
            ->expects($this->once())
            ->method('deleteTemporaryIndex')
            ->with($hashId, $indexName)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->deleteTemporaryIndex($hashId, $indexName);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testReplaceIndexSuccess()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $httpResponse = HttpResponse::create(HttpStatusCode::OK);
        $httpResponse->setBody(['status' => 'OK']);

        $this->indexResource
            ->expects($this->once())
            ->method('replaceIndex')
            ->with($hashId, $indexName)
            ->willReturn($httpResponse);

        $managementClient = $this->createSut();
        $response = $managementClient->replaceIndex($hashId, $indexName);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertSame(['status' => 'OK'], $response->getBody());
    }

    public function testReplaceIndexNoAuthorization()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->indexResource
            ->expects($this->once())
            ->method('replaceIndex')
            ->with($hashId, $indexName)
            ->willThrowException($this->unauthorizedException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->replaceIndex($hashId, $indexName);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::UNAUTHORIZED, $e->getCode());
            $this->assertSame('The user hasn\'t provided valid authorization.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }

    public function testReplaceIndexNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $indexName = 'index_test';

        $this->indexResource
            ->expects($this->once())
            ->method('replaceIndex')
            ->with($hashId, $indexName)
            ->willThrowException($this->notFoundException);

        $managementClient = $this->createSut();
        $thrownException = false;

        try {
            $managementClient->replaceIndex($hashId, $indexName);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            $this->assertSame('Not Found.', $e->getMessage());
        }

        $this->assertTrue($thrownException);
    }
}