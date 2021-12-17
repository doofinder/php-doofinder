<?php

namespace Doofinder\Management\Model;

class DataSourceOptionMagento extends DataSourceOption
{
    /**
     * @param array $data
     * @return DataSourceOptionMagento
     */
    public static function createFromArray(array $data)
    {
        return new self($data['url']);
    }

    /**
     * @return string[]
     */
    public function jsonSerialize()
    {
        return [
            'url' => $this->url
        ];
    }
}