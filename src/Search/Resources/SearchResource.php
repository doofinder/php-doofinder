<?php

namespace Doofinder\Search\Resources;

use Doofinder\Shared\Resource;

abstract class SearchResource extends Resource
{
    /**
     * Returns versioned url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->config->getBaseUrl() . '/6';
    }
}