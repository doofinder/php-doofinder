<?php

namespace Doofinder\Management\Resources;

use Doofinder\Shared\Resource;

abstract class ManagementResource extends Resource
{
    /**
     * Returns versioned url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->config->getBaseUrl() . '/api/v2';
    }
}