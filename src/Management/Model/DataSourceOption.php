<?php

namespace Doofinder\Management\Model;

use Doofinder\Shared\Interfaces\ModelInterface;

abstract class DataSourceOption implements ModelInterface
{
    /**
     * @var string|null
     */
    protected $url;

    /**
     * @param string|null $url
     */
    protected function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }
}