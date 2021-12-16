<?php

namespace Doofinder\Shared\Services;

use DateTime;

class Jwt
{
    const HEADER = '{"alg": "HS256", "typ": "JWT"}';

    public static function generateToken($name, $secret)
    {
        $payload = json_encode(
            [
                'exp' => (new DateTime('+15 minutes'))->getTimestamp(),
                'iat' => (new DateTime())->getTimestamp(),
                'name' => $name
            ]
        );

        $headerPayload = self::base64url_encode(self::HEADER). '.' . self::base64url_encode($payload);
        $signature = hash_hmac(
            'sha256',
            $headerPayload,
            $secret,
            true
        );

        return $headerPayload . '.' . self::base64url_encode($signature);
    }

    private static function base64url_encode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}