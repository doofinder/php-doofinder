<?php

use Doofinder\Shared\Services\Validator\Validations\StringValidation;
use Doofinder\Management\ManagementClient;

return [
    'currency' => StringValidation::create()->availableValues(ManagementClient::CURRENCY),
    'language' => StringValidation::create()->required(), //TODO poner los posible valores??
    'name' => StringValidation::create()->required(),
    'site_url' => StringValidation::create(),
    'stopwords',
    'platform',
    'has_grouping'
];


