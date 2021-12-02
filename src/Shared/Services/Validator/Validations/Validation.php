<?php

namespace Doofinder\Shared\Services\Validator\Validations;

use Doofinder\Shared\Exceptions\ValidationException;

abstract class Validation
{
    protected $isRequired = false;

    abstract public function validate($params);

    /**
     * @return $this
     */
    public function required()
    {
        $this->isRequired = true;

        return $this;
    }

    /**
     * @param mixed $param
     * @return bool
     */
    protected function hasToCheck($param)
    {
        if (!$this->isRequired and is_null($param)) {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $param
     * @throws ValidationException
     */
    protected function checkIsRequired($param)
    {
        if ($this->isRequired && is_null($param)) {
            throw new ValidationException('Param required');
        }
    }
}