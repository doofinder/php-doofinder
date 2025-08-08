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
        
        // Set very short timeout for testing (1 millisecond)
        curl_setopt($s, CURLOPT_TIMEOUT, 1);
        curl_setopt($s, CURLOPT_CONNECTTIMEOUT, 1);

        if (($response = curl_exec($s)) === false) {
            $error = curl_error($s);
            $errno = curl_errno($s);
            curl_close($s);
            
            // Map CURL error codes to appropriate HTTP status codes
            $httpCode = 500; // Default to 500 for unknown errors
            
            switch ($errno) {
                case CURLE_OPERATION_TIMEDOUT:
                case CURLE_OPERATION_TIMEOUTED:
                    $httpCode = 408; // Request Timeout
                    break;
                case CURLE_COULDNT_CONNECT:
                case CURLE_COULDNT_RESOLVE_HOST:
                    $httpCode = 503; // Service Unavailable
                    break;
                case CURLE_SSL_CONNECT_ERROR:
                case CURLE_SSL_CACERT:
                    $httpCode = 495; // SSL Certificate Error
                    break;
                default:
                    $httpCode = 500; // Internal Server Error
                    break;
            }
            
            throw new RequestException('curl_error: ' . $error, $httpCode);
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
        $header = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        if ($headers) {
            $header = array_merge($header, $headers);
        }

        return $header;
    }
}