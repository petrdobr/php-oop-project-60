<?php

namespace Hexlet\Validator\Types;

class NumberType
{
    private $validator;
    private int $id;
    public int $startNumber;
    public int $endNumber;
    private $function;
    private $value;
    //Didn't come up with anything better than flags :(
    public $flags = [
        'required' => false,
        'positive' => false,
        'range' => false,
        'test' => false
    ];
    public $validity = [
        'required' => false,
        'positive' => false,
        'range' => false,
        'test' => false
    ];

    public function __construct($validator, $id)
    {
        $this->id = $id;
        $this->validator = $validator;
    }

    public function required()
    {
        $this->flags['required'] = true;
        return $this;
    }

    public function positive()
    {
        $this->flags['positive'] = true;
        return $this;
    }

    public function range(int $startNumber, int $endNumber)
    {
        $this->flags['range'] = true;
        $this->startNumber = $startNumber;
        $this->endNumber = $endNumber;
        return $this;
    }
    public function test($name, $value)
    {
        $this->function = $this->validator->getFunctionByName($name);
        $this->value = $value;
        $this->flags['test'] = true;
        return $this;
    }

    public function isValid($data)
    {
        if ($this->flags['required']) {
            $this->validity['required'] = (is_int($data) && $data != null) ? true : false;
        }
        if ($this->flags['positive']) {
            $this->validity['positive'] = ($data >= 0) ? true : false;
        }
        if ($this->flags['range']) {
            $this->validity['range'] = ($data > $this->startNumber && $data < $this->endNumber) ? true : false;
        }
        if ($this->flags['test']) {
            $fn = $this->function;
            $result = $fn($data, $this->value);
            $this->validity['test'] = ($result) ? true : false;
        }

        //check each validity flag and compare with methods flag, if they are not coincident then false
        //otherwise - true
        foreach ($this->validity as $option => $flag) {
            if ($this->flags[$option] == true) {
                if ($flag == false) {
                    return false;
                }
            }
        }
        //check didn't return false then it's true
        return true;
    }
}
