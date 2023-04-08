<?php

namespace Hexlet\Validator;

use Hexlet\Validator\Types\StringType;
use Hexlet\Validator\Types\IntType;

class Validator
{
    private $type;
    private int $idString;
    private int $idInteger;

    public function __construct()
    {
        $this->idString = 1;
        $this->idInteger = 1;
    }

    public function string()
    {
        $stringObject = new StringType($this, $this->idString);
        $this->idString++;
        return $stringObject;
    }

    public function number()
    {
        $numberObject = new IntType($this, $this->idInteger);
        $this->idInteger++;
        return $numberObject;
    }
}
