<?php

namespace Doofinder\Management\Model;

/**
 * Model with data of a given "ekm" data source option
 */
class DataSourceOptionEkm extends DataSourceOption
{
    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @param array $data
     * @return DataSourceOptionEkm
     */
    public static function createFromArray(array $data)
    {
        $entity = new self(array_key_exists('url', $data)? $data['url'] : null);
        $entity->refreshToken = $data['refresh_token'];

        return $entity;
    }

    /**
     * @return string[]
     */
    public function jsonSerialize(): array
    {
        return [
            'url' => $this->url,
            'refresh_token' => $this->refreshToken
        ];
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }
}