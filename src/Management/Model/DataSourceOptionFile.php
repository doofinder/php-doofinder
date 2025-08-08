<?php

namespace Doofinder\Management\Model;

/**
 * Model with data of a given "file" data source option
 */
class DataSourceOptionFile extends DataSourceOption
{
    /**
     * @var integer|null
     */
    private $pageSize = null;

    /**
     * @param array $data
     * @return DataSourceOptionFile
     */
    public static function createFromArray(array $data)
    {
        $entity = new self($data['url']);
        $entity->pageSize = array_key_exists('page_size', $data)? $data['page_size'] : null;

        return $entity;
    }

    /**
     * @return string[]
     */
    public function jsonSerialize(): array
    {
        return [
            'url' => $this->url,
            'page_size' => $this->pageSize
        ];
    }

    /**
     * @return int|null
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }
}