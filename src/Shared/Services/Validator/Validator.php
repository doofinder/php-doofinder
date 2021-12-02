<?php

namespace Doofinder\Shared\Services\Validator;

use Doofinder\Shared\Exceptions\ValidatorException;
use Doofinder\Shared\Services\Validator\Validations\Validation;

class Validator
{
    private $errors = [];

    public static function create()
    {
        return new self();
    }

    /**
     * @param array<string,mixed> $params
     * @param array<Validation> $validations
     * @throws ValidatorException
     */
    public function validateParams(array $params, array $validations)
    {
        //$inputs = filter_var_array($params, $validations);
        /*$params = ['product_id' => '123'];
        $validations = ['product_id' => [
            'filter' => FILTER_VALIDATE_INT,
            'flags' => FILTER_NULL_ON_FAILURE,
            'options' => null
        ]];
        $res = filter_var_array($params, $validations);
        var_dump($res);
        die('seerf');*/
        foreach ($validations as $key => $validation)
        {
            try {
                $validation->validate(array_key_exists($key, $params) ? $params[$key] : null);
            } catch(\Exception $e) {
                $this->errors[$key] = $e->getMessage();
            }
        }

        if (!empty($this->errors)) {
            throw new ValidatorException(json_encode($this->errors));
        }
    }
}