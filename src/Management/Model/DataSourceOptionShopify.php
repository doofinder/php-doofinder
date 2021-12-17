<?php

namespace Doofinder\Management\Model;

class DataSourceOptionShopify extends DataSourceOption
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param array $data
     * @return DataSourceOptionShopify
     */
    public static function createFromArray(array $data)
    {
        $entity = new self($data['url']);
        $entity->token = $data['token'];

        return $entity;
    }

    /**
     * @return string[]
     */
    public function jsonSerialize()
    {
        return [
            'url' => $this->url,
            'token' => $this->token
        ];
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}