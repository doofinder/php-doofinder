<?php

namespace Doofinder\Shared\Interfaces;

interface ModelInterface extends \JsonSerializable
{
    /**
     * @param array $data
     * @return ModelInterface
     */
    public static function createFromArray(array $data);
}