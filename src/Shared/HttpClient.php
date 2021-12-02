<?php

namespace Doofinder\Shared;

use Doofinder\Shared\Interfaces\HttpClientInterface;

class HttpClient implements HttpClientInterface
{

    public function request($url, $method, $params, $options)
    {
        $s = curl_init();
        curl_setopt($s,CURLOPT_URL,$url);

        if($method === 'POST') {
            curl_setopt($s,CURLOPT_POST,true);
            curl_setopt($s,CURLOPT_POSTFIELDS, $params);
        }

        curl_exec($s);
        $status = curl_getinfo($s);
        curl_close($s);
    }
}