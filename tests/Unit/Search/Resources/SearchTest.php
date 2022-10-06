<?php

namespace Tests\Unit\Search\Resources;

use Doofinder\Search\Resources\Search;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpResponse;
use Doofinder\Shared\HttpStatusCode;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Shared\Interfaces\HttpResponseInterface;
use Tests\Unit\Management\Resources\BaseResourceTest;

class SearchTest extends BaseResourceTest
{
    private function createSut()
    {
        return Search::create($this->httpClient, $this->config);
    }

    private function getUrl($hashId)
    {
        return self::BASE_URL . '/6/' . $hashId;
    }

    public function testSearchSuccess()
    {
        $body = [
            'custom_results_id' => 5,
            'facets' => [
                [
                    'brand' => [
                        'terms' => [
                            [
                                'name' => 'Adidas',
                                'count' => 5
                            ],
                            [
                                'name' => 'Nike',
                                'count' => 3
                            ],
                        ],
                        'selected' => []
                    ]
                ]
            ],
            'query_name' => 'fuzzy',
            'results' => [
                [
                    'description' => 'The best product description ever',
                    'dfid' => '1234567890abcdef1234567890abcdef@product@1234567890abcdef',
                    'id' => 'my_id',
                    'image_url' => 'http://www.example.com/images/alt_product_image.jpg',
                    'title' => 'My item',
                    'url' => 'http://www.example.com/alt_product_description.htm'
                ]
            ],
            'total' => 1
        ];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $params = [
            'auto_params' =>  true,
            'custom_results' =>  false,
            'excluded_results' =>  false,
            'exclude' =>  [
                'color' => ['blue', 'red']
            ],
            'facets' => [
                'brand' => [
                    'size' => 10,
                    'field' => 'best_price',
                ]
            ],
            'filter' => '',
            'filter_execution' => 'and',
            'indices' => ['product', 'page'],
            'page' => 1,
            'query' => '',
            'query_name' => 'match_and',
            'rpp' => 1,
            'session_id' => 'rand_fake_session_id',
            'stats' => false,
            'skip_auto_filters' => '',
            'skip_top_facet' => '',
            'sort' => [
                'price' => 'desc'
            ],
            'title_facet' => true,
            'top_facet' => true
        ];

        $hashId = '3a0811e861d36f76cedca60723e03291';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId) . '/_search', HttpClientInterface::METHOD_GET, $params, ['Authorization: Token ' . self::TOKEN])
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->search($hashId, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertEquals($response->getBody(), $body);
    }

    public function testSearchInvalidParams()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error" : {"code": "bad_params"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $params = [
            'fake_param' =>  'fake_value',
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId) . '/_search', HttpClientInterface::METHOD_GET, $params, ['Authorization: Token ' . self::TOKEN])
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->search($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('bad_params', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testSearchHashIdNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');
        $params = [];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId) . '/_search', HttpClientInterface::METHOD_GET, $params, ['Authorization: Token ' . self::TOKEN])
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->search($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testSuggestSuccess()
    {
        $body = [
            'Sugg',
            'Suggest',
            'Suggestion',
        ];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $params = [
            'indices' =>  [],
            'query' =>  'Sug',
            'session_id' =>  'sdfiwyuehfiuwehf',
            'stats' => false
        ];

        $hashId = '3a0811e861d36f76cedca60723e03291';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId) . '/_suggest', HttpClientInterface::METHOD_GET, $params, ['Authorization: Token ' . self::TOKEN])
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->suggest($hashId, $params);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertEquals($response->getBody(), $body);
    }

    public function testSuggestInvalidParams()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error" : {"code": "bad_params"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $params = [
            'fake_param' =>  'fake_value',
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId) . '/_suggest', HttpClientInterface::METHOD_GET, $params, ['Authorization: Token ' . self::TOKEN])
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->suggest($hashId, $params);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('bad_params', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testSuggestHashIdNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');
        $params = [];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with($this->getUrl($hashId) . '/_suggest', HttpClientInterface::METHOD_GET, $params, ['Authorization: Token ' . self::TOKEN])
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->suggest($hashId, $params);
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