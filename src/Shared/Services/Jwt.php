<?php

namespace Doofinder\Shared\Services;

use DateTime;

/**
 * Utility to generate a Json Web Token (JWT) to do requests to Doofinder API services
 */
class Jwt
{
    const HEADER = '{"alg": "HS256", "typ": "JWT"}';

    /**
     * Given an api token and secret generates a JWT
     *
     * @param string $name
     * @param string $secret
     * @return string
     */
    public static function generateToken($secret, $name)
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

    /**
     * @param string $data
     * @return array|string|string[]
     */
    private static function base64url_encode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}