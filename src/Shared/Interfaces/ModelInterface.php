<?php

namespace Doofinder\Shared\Interfaces;

/**
 * Model's interface
 */
interface ModelInterface extends \JsonSerializable
{
    /**
     * @param array $data
     * @return ModelInterface
     */
    public static function createFromArray(array $data);
}