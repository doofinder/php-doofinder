<?php

namespace Doofinder\Shared;

use Doofinder\Configuration;
use Doofinder\Shared\Exceptions\ApiException;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Shared\Interfaces\HttpResponseInterface;
use Doofinder\Shared\Interfaces\ModelInterface;
use Doofinder\Shared\Services\Jwt;

/**
 * A resource class is in charge to communicate with the API and return a formatted response
 */
abstract class Resource
{
    protected $httpClient;
    protected $config;
    protected $baseUrl;

    /**
     * @param HttpClientInterface $httpClient
     * @param Configuration $config
     */
    protected function __construct(HttpClientInterface $httpClient, Configuration $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
        $this->baseUrl = $config->getBaseUrl() . '/api/v2';
    }

    /**
     * It does a http request with Jwt authentication. If there is an error throws an ApiException
     *
     * @param string $url
     * @param string $method
     * @param class-string<ModelInterface> $model
     * @param array $params
     * @param array $headers
     * @return HttpResponseInterface
     * @throws ApiException
     */
    protected function requestWithJwt($url, $method, $model = null, array $params = [], array $headers = [])
    {
        $jwtToken = Jwt::generateToken($this->config->getToken(), $this->config->getUserId());

        /** @var HttpResponseInterface $response */
        $response = $this->httpClient->request(
            $url,
            $method,
            $params,
            array_merge($headers, ["Authorization: Bearer {$jwtToken}"])
        );

        if ($response->getStatusCode() < HttpStatusCode::OK || $response->getStatusCode() >= HttpStatusCode::BAD_REQUEST) {
            $message = json_encode($response->getBody());

            if ($message === false) {
                $message = '{"error": {"code" : "Something went wrong"}}';
            }

            throw new ApiException($message, $response->getStatusCode(), null, $response);
        }

        if (!is_null($model)) {
            $response->setBody($model::createFromArray($response->getBody()));
        }

        return $response;
    }
}