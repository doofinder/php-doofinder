<?php

namespace Doofinder\Management\Resources;

class Items
{
    private function __construct()
    {
    }

    public static function create()
    {
        return new self();
    }
}