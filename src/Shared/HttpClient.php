<?php

namespace Doofinder\Shared;

use Doofinder\Shared\Exceptions\RequestException;
use Doofinder\Shared\Interfaces\HttpClientInterface;
use Doofinder\Shared\Interfaces\HttpResponseInterface;

/**
 * Class to do requests though curl utility
 */
class HttpClient implements HttpClientInterface
{
    /**
     * @param string $url
     * @param string $method
     * @param array $params
     * @param array $headers
     * @return HttpResponseInterface
     * @throws RequestException
     */
    public function request($url, $method, $params, $headers)
    {
        $s = curl_init();
        if ($method === self::METHOD_GET) {
            $url .= '?' . http_build_query($params);
        } else {
            curl_setopt($s,CURLOPT_POSTFIELDS, json_encode($params));
        }

        curl_setopt($s,CURLOPT_URL, $url);
        curl_setopt($s,CURLOPT_NOBODY, false);
        curl_setopt($s,CURLOPT_POST, ($method === self::METHOD_GET)? 0 : 1);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($s, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($s, CURLOPT_HTTPHEADER, $this->getHeader($headers));

        if (($response = curl_exec($s)) === false) {
            $error = curl_error($s);
            curl_close($s);
            throw new RequestException('curl_error', $error);
        } else {
            $response = HttpResponse::create(curl_getinfo($s)['http_code'], $response);
        }

        curl_close($s);

        return $response;
    }

    /**
     * It generates the http header
     *
     * @param array $headers
     * @return string[]
     */
    private function getHeader($headers)
    {
        $header = ['Content-Type: application/json'];
        if ($headers) {
            $header = array_merge($header, $headers);
        }

        return $header;
    }
}