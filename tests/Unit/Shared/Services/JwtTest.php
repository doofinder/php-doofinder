<?php

namespace Tests\Unit\Shared\Services;

use Doofinder\Shared\Services\Jwt;

class JwtTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateToken()
    {
        $name = 'This is a fake name';
        $secret = 'This is a fake secret';

        $jwtToken = Jwt::generateToken($name, $secret);

        $jwtValues = explode('.', $jwtToken);

        // Check header structure
        $header = $this->base64url_decode($jwtValues[0]);
        $this->assertSame($header, Jwt::HEADER);

        //Check payload structure
        $payload = json_decode($this->base64url_decode($jwtValues[1]), true);
        $this->assertArrayHasKey('exp', $payload);
        $this->assertArrayHasKey('iat', $payload);
        $this->assertArrayHasKey('name', $payload);
        $this->assertSame($name, $payload['name']);

        // generate signature to compare it
        $expectedSignature = $this->base64url_encode(hash_hmac('sha256', $jwtValues[0] . '.' . $jwtValues[1], $secret, true));

        $this->assertSame($expectedSignature, $jwtValues[2]);
    }

    private function base64url_encode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private function base64url_decode($data)
    {
        return base64_decode(str_replace(['-', '_', ''], ['+', '/', '='], $data));
    }
}