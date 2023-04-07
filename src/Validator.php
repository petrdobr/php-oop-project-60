<?php

namespace Hexlet\Validator;

Use Hexlet\Validator\Types\StringType;

class Validator
{
    private $type;
    private int $idString;

    public function __construct()
    {
        $this->idString = 1;
    }

    public function string()
    {
        $stringObject = new StringType($this, $this->idString);
        $this->idString++;
        return $stringObject;
    }

}
