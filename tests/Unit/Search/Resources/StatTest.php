<?php

namespace Tests\Unit\Search\Resources;

use Doofinder\Search\Resources\Stat;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\HttpResponse;
use Doofinder\Shared\HttpStatusCode;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Shared\Interfaces\HttpResponseInterface;
use Tests\Unit\Management\Resources\BaseResourceTest;

class StatTest extends BaseResourceTest
{
    private function createSut()
    {
        return Stat::create($this->httpClient, $this->config);
    }

    private function getUrl($hashId)
    {
        return self::BASE_URL . '/6/' . $hashId . '/stats';
    }

    public function testInitSessionSuccess()
    {
        $body = ['result' => 'registered'];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $sessionId = 'rand_fake_session_id';
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/init',
                HttpClientInterface::METHOD_PUT,
                ['session_id' => $sessionId],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->initSession($hashId, $sessionId);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertEquals($response->getBody(), $body);
    }

    public function testInitSessionInvalidParams()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error" : {"code": "bad_params"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'rand_fake_session_id';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/init',
                HttpClientInterface::METHOD_PUT,
                ['session_id' => $sessionId],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->initSession($hashId, $sessionId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('bad_params', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testInitSessionHashIdNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');
        $sessionId = 'rand_fake_session_id';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/init',
                HttpClientInterface::METHOD_PUT,
                ['session_id' => $sessionId],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->initSession($hashId, $sessionId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testLogCheckoutSuccess()
    {
        $body = ['result' => 'registered'];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $sessionId = 'rand_fake_session_id';
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/checkout',
                HttpClientInterface::METHOD_PUT,
                ['session_id' => $sessionId],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->logCheckout($hashId, $sessionId);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertEquals($response->getBody(), $body);
    }

    public function testLogCheckoutInvalidParams()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error" : {"code": "bad_params"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'rand_fake_session_id';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/checkout',
                HttpClientInterface::METHOD_PUT,
                ['session_id' => $sessionId],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->logCheckout($hashId, $sessionId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('bad_params', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testLogCheckoutHashIdNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');
        $sessionId = 'rand_fake_session_id';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/checkout',
                HttpClientInterface::METHOD_PUT,
                ['session_id' => $sessionId],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->logCheckout($hashId, $sessionId);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function optionalQueryParameterProvider()
    {
        return [
            [null, []],
            ['fake_query', ['query' => 'fake_query']],
        ];
    }

    /**
     * @param string|null $query
     * @param array<string> $params
     * @dataProvider optionalQueryParameterProvider
     */
    public function testLogRedirectionSuccess($query, $params)
    {
        $body = ['result' => 'registered'];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $sessionId = 'rand_fake_session_id';
        $id = 'fake_id';
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $params = array_merge(
            [
                'session_id' => $sessionId,
                'id' => $id,
            ],
            $params
        );

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/redirect',
                HttpClientInterface::METHOD_PUT,
                $params,
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->logRedirection($hashId, $sessionId, $id, $query);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertEquals($response->getBody(), $body);
    }

    public function testLogRedirectionInvalidParams()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error" : {"code": "bad_params"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'rand_fake_session_id';
        $id = 'fake_id';
        $query = 'fake_query';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/redirect',
                HttpClientInterface::METHOD_PUT,
                [
                    'session_id' => $sessionId,
                    'id' => $id,
                    'query' => $query
                ],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->logRedirection($hashId, $sessionId, $id, $query);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('bad_params', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testLogRedirectionHashIdNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');
        $sessionId = 'rand_fake_session_id';
        $id = 'fake_id';
        $query = 'fake_query';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/redirect',
                HttpClientInterface::METHOD_PUT,
                [
                    'session_id' => $sessionId,
                    'id' => $id,
                    'query' => $query
                ],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->logRedirection($hashId, $sessionId, $id, $query);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    /**
     * @param string|null $query
     * @param array<string> $params
     * @dataProvider optionalQueryParameterProvider
     */
    public function testLogBannerSuccess($query, $params)
    {
        $body = ['result' => 'registered'];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $sessionId = 'rand_fake_session_id';
        $id = 'fake_id';
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $params = array_merge(
            [
                'session_id' => $sessionId,
                'id' => $id,
            ],
            $params
        );

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/image',
                HttpClientInterface::METHOD_PUT,
                $params,
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->logBanner($hashId, $sessionId, $id, $query);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertEquals($response->getBody(), $body);
    }

    public function testLogBannerInvalidParams()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error" : {"code": "bad_params"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'rand_fake_session_id';
        $id = 'fake_id';
        $query = 'fake_query';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/image',
                HttpClientInterface::METHOD_PUT,
                [
                    'session_id' => $sessionId,
                    'id' => $id,
                    'query' => $query
                ],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->logBanner($hashId, $sessionId, $id, $query);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('bad_params', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testLogBannerHashIdNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');
        $sessionId = 'rand_fake_session_id';
        $id = 'fake_id';
        $query = 'fake_query';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/image',
                HttpClientInterface::METHOD_PUT,
                [
                    'session_id' => $sessionId,
                    'id' => $id,
                    'query' => $query
                ],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->logBanner($hashId, $sessionId, $id, $query);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    /**
     * @param string|null $query
     * @param array<string> $params
     * @dataProvider optionalQueryParameterProvider
     */
    public function testLogClickSuccess($query, $params)
    {
        $body = ['result' => 'registered'];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $sessionId = 'rand_fake_session_id';
        $itemId = 'fake_id';
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $params = array_merge(
            [
                'session_id' => $sessionId,
                'dfid' => $itemId,
            ],
            $params
        );

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/click',
                HttpClientInterface::METHOD_PUT,
                $params,
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->logClick($hashId, $sessionId, $itemId, $query);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertEquals($response->getBody(), $body);
    }

    public function testLogClickInvalidParams()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error" : {"code": "bad_params"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'rand_fake_session_id';
        $itemId = 'fake_id';
        $query = 'fake_query';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/click',
                HttpClientInterface::METHOD_PUT,
                [
                    'session_id' => $sessionId,
                    'id' => $itemId,
                    'query' => $query
                ],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->logClick($hashId, $sessionId, $itemId, $query);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('bad_params', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testLogClickHashIdNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');
        $sessionId = 'rand_fake_session_id';
        $itemId = 'fake_id';
        $query = 'fake_query';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/click',
                HttpClientInterface::METHOD_PUT,
                [
                    'session_id' => $sessionId,
                    'id' => $itemId,
                    'query' => $query
                ],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->logClick($hashId, $sessionId, $itemId, $query);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testLogAddToCartSuccess()
    {
        $body = ['result' => 'registered'];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $sessionId = 'rand_fake_session_id';
        $id = 'fake_id';
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $amount = 2;
        $indexName = 'fake_index';
        $price = 123.56;
        $title = 'fake_title';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/cart/' . $sessionId,
                HttpClientInterface::METHOD_PUT,
                [
                    'amount' => $amount,
                    'id' => $id,
                    'index' => $indexName,
                    'price' => $price,
                    'title' => $title,
                ],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->logAddToCart($hashId, $sessionId, $amount, $id, $indexName, $price, $title);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertEquals($response->getBody(), $body);
    }

    public function testLogAddToCartInvalidParams()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error" : {"code": "bad_params"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'rand_fake_session_id';
        $id = 'fake_id';
        $amount = 2;
        $indexName = 'fake_index';
        $price = 123.56;
        $title = 'fake_title';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/cart/' . $sessionId,
                HttpClientInterface::METHOD_PUT,
                [
                    'amount' => $amount,
                    'id' => $id,
                    'index' => $indexName,
                    'price' => $price,
                    'title' => $title,
                ],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->logAddToCart($hashId, $sessionId, $amount, $id, $indexName, $price, $title);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('bad_params', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testLogAddToCartHashIdNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');
        $sessionId = 'rand_fake_session_id';
        $id = 'fake_id';
        $amount = 2;
        $indexName = 'fake_index';
        $price = 123.56;
        $title = 'fake_title';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/cart/' . $sessionId,
                HttpClientInterface::METHOD_PUT,
                [
                    'amount' => $amount,
                    'id' => $id,
                    'index' => $indexName,
                    'price' => $price,
                    'title' => $title,
                ],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->logAddToCart($hashId, $sessionId, $amount, $id, $indexName, $price, $title);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::NOT_FOUND, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('not_found', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testLogRemoveFromCartSuccess()
    {
        $body = ['result' => 'registered'];

        $response = HttpResponse::create(HttpStatusCode::OK, json_encode($body));

        $sessionId = 'rand_fake_session_id';
        $id = 'fake_id';
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $amount = 2;
        $indexName = 'fake_index';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/cart/' . $sessionId,
                HttpClientInterface::METHOD_PATCH,
                [
                    'amount' => $amount,
                    'id' => $id,
                    'index' => $indexName,
                ],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $response = $this->createSut()->logRemoveFromCart($hashId, $sessionId, $amount, $id, $indexName);

        $this->assertSame(HttpStatusCode::OK, $response->getStatusCode());
        $this->assertEquals($response->getBody(), $body);
    }

    public function testLogRemoveFromCartInvalidParams()
    {
        $response = HttpResponse::create(HttpStatusCode::BAD_REQUEST, '{"error" : {"code": "bad_params"}}');
        $hashId = '3a0811e861d36f76cedca60723e03291';
        $sessionId = 'rand_fake_session_id';
        $id = 'fake_id';
        $amount = 2;
        $indexName = 'fake_index';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/cart/' . $sessionId,
                HttpClientInterface::METHOD_PATCH,
                [
                    'amount' => $amount,
                    'id' => $id,
                    'index' => $indexName,
                ],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->logRemoveFromCart($hashId, $sessionId, $amount, $id, $indexName);
        } catch (ApiException $e) {
            $thrownException = true;
            $this->assertSame(HttpStatusCode::BAD_REQUEST, $e->getCode());
            /** @var HttpResponseInterface $response */
            $response = $e->getBody();
            $this->assertSame('bad_params', $response->getBody()['error']['code']);
        }

        $this->assertTrue($thrownException);
    }

    public function testLogRemoveFromCartHashIdNotFound()
    {
        $hashId = '3a0811e861d36f76cedca60723e03291';

        $response = HttpResponse::create(HttpStatusCode::NOT_FOUND, '{"error" : {"code": "not_found"}}');
        $sessionId = 'rand_fake_session_id';
        $id = 'fake_id';
        $amount = 2;
        $indexName = 'fake_index';

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->getUrl($hashId) . '/cart/' . $sessionId,
                HttpClientInterface::METHOD_PATCH,
                [
                    'amount' => $amount,
                    'id' => $id,
                    'index' => $indexName,
                ],
                ['Authorization: Token ' . self::TOKEN]
            )
            ->willReturn($response);

        $this->setConfig();

        $thrownException = false;

        try {
            $this->createSut()->logRemoveFromCart($hashId, $sessionId, $amount, $id, $indexName);
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