<?php

namespace Hexlet\Validator;

use Hexlet\Validator\Types\StringType;
use Hexlet\Validator\Types\NumberType;
use Hexlet\Validator\Types\ArrayType;

class Validator
{
    private int $idString;
    private int $idInteger;
    private int $idArray;
    private array $functions = [];

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
        $numberObject = new NUmberType($this, $this->idInteger);
        $this->idInteger++;
        return $numberObject;
    }

    public function array()
    {
        $ArrayObject = new ArrayType($this, $this->idArray);
        $this->idArray++;
        return $ArrayObject;
    }

    public function addValidator($type, $name, $fn)
    {
        $types = ['string', 'number', 'array'];
        if (in_array($type, $types)) {
            $this->functions += [$name => $fn];
        } else {
            throw new \Exception('No such type, sorry');
        }
        return $this;
    }

    public function getFunctionByName(string $name)
    {
        return $this->functions[$name];
    }
}
