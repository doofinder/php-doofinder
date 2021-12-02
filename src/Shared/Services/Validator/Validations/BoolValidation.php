<?php

namespace Doofinder\Shared\Services\Validator\Validations;

use Doofinder\Shared\Exceptions\ValidationException;

class BoolValidation extends Validation
{
    /**
     * @return BoolValidation
     */
    public static function create()
    {
        return new self();
    }

    /**
     * @param mixed $param
     * @throws ValidationException
     */
    public function validate($param)
    {
        if (!$this->hasToCheck($param)) {
            return;
        }
        $this->checkIsRequired($param);
        $this->checkIsBoolean($param);
    }

    /**
     * @param mixed $param
     * @throws ValidationException
     */
    private function checkIsBoolean($param)
    {
        if ($param !== true && $param !== false) {
            throw new ValidationException('Must be a boolean');
        }
    }
}