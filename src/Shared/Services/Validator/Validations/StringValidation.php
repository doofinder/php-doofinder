<?php

namespace Doofinder\Shared\Services\Validator\Validations;

use Doofinder\Shared\Exceptions\ValidationException;

class StringValidation extends Validation
{
    private $availableValues = [];

    public static function create()
    {
        return new self();
    }

    public function validate($param)
    {
        if (!$this->hasToCheck($param)) {
            return;
        }
        $this->checkIsRequired($param);
        $this->checkIsString($param);
    }

    /**
     * @param array $availableValues
     * @return $this
     */
    public function availableValues(array $availableValues)
    {
        $this->availableValues = $availableValues;

        return $this;
    }

    /**
     * @param mixed $param
     * @throws ValidationException
     */
    private function checkIsString($param)
    {
        if (!is_string($param)) {
            throw new ValidationException('Must be a string');
        }
    }

    /**
     * @param mixed $param
     * @throws ValidationException
     */
    private function checkAvailableValues($param)
    {
        if (!empty($this->availableValues) && in_array($param, $this->availableValues)) {
            throw new ValidationException('Must be a valid value: ' . implode(', ', $this->availableValues));
        }
    }
}