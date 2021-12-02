<?php

namespace Doofinder\Shared\Services\Validator\Validations;

use Doofinder\Shared\Exceptions\ValidationException;

class IntegerValidation extends Validation
{
    /**
     * @var int|null
     */
    private $max = null;
    /**
     * @var int|null
     */
    private $min = null;

    /**
     * @return IntegerValidation
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
        $this->checkIsInteger($param);
        $this->checkMax($param);
        $this->checkMin($param);
    }

    /**
     * @param int $maxValue
     * @return $this
     */
    public function max($maxValue)
    {
        $this->max = $maxValue;

        return $this;
    }

    /**
     * @param int $minValue
     * @return $this
     */
    public function min($minValue)
    {
        $this->min = $minValue;

        return $this;
    }

    /**
     * @param int $param
     * @throws ValidationException
     */
    private function checkMax($param)
    {
        if (!is_null($this->max) && $param > $this->max) {
            throw new ValidationException('Must be lower than ' . $this->max);
        }
    }

    /**
     * @param int $param
     * @throws ValidationException
     */
    private function checkMin($param)
    {
        if (!is_null($this->min) && $param < $this->min) {
            throw new ValidationException('Must be higher than ' . $this->min);
        }
    }

    /**
     * @param mixed $param
     * @throws ValidationException
     */
    private function checkIsInteger($param)
    {
        if (!is_integer($param)) {
            throw new ValidationException('Must be an integer');
        }
    }
}