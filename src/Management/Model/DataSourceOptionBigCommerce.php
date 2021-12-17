<?php

namespace Doofinder\Management\Model;

class DataSourceOptionBigCommerce extends DataSourceOption
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $storeHash;

    /**
     * @param array $data
     * @return DataSourceOptionBigCommerce
     */
    public static function createFromArray(array $data)
    {
        $entity = new self(array_key_exists('url', $data)? $data['url'] : null);
        $entity->accessToken = $data['access_token'];
        $entity->storeHash = $data['store_hash'];

        return $entity;
    }

    /**
     * @return string[]
     */
    public function jsonSerialize()
    {
        return [
            'url' => $this->url,
            'access_token' => $this->accessToken,
            'store_hash' => $this->storeHash
        ];
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getStoreHash()
    {
        return $this->storeHash;
    }
}