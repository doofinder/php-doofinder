<?php

namespace Doofinder\Management\Resources;

class Indexes
{
    private function __construct()
    {
    }

    public static function create()
    {
        return new self();
    }
}