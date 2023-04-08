<?php

namespace Hexlet\Validator;

use Hexlet\Validator\Types\StringType;
use Hexlet\Validator\Types\IntType;
use Hexlet\Validator\Types\ArrayType;

class Validator
{
    private int $idString;
    private int $idInteger;
    private int $idArray;

    public function __construct()
    {
        $this->idString = 1;
        $this->idInteger = 1;
        $this->idArray = 1;
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

    public function array()
    {
        $ArrayObject = new ArrayType($this, $this->idArray);
        $this->idArray++;
        return $ArrayObject;
    }
}
