<?php

namespace Doofinder\Management\Model;

/**
 * Model with data of a given "magento" data source option
 */
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
    public function jsonSerialize(): array
    {
        return [
            'url' => $this->url
        ];
    }
}