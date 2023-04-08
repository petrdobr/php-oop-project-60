<?php

namespace Hexlet\Validator\Types;

class ArrayType
{
    private $validator;
    private int $id;
    public int $checkSize;
    private $function;
    private $value;
    //Didn't come up with anything better than flags :(
    public $flags = [
        'required' => false,
        'sizeof' => false,
        'shape' => false,
        'test' => false
    ];
    public $validity = [
        'required' => false,
        'sizeof' => false,
        'shape' => false,
        'test' => false
    ];
    private $shapeArray;

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

    public function sizeof(int $size)
    {
        $this->flags['sizeof'] = true;
        $this->checkSize = $size;
        return $this;
    }

    public function shape($array)
    {
        $this->flags['shape'] = true;
        $this->shapeArray = $array;
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
            $this->validity['required'] = (is_array($data) && $data !== null) ? true : false;
        }
        if ($this->flags['sizeof']) {
            $this->validity['sizeof'] = (count($data) == $this->checkSize) ? true : false;
        }
        if ($this->flags['shape']) {
            $checkArray = [];
            //collect results for checking values
            foreach ($this->shapeArray as $key => $checkOption) {
                $checkArray[] = $checkOption->isValid($data[$key]);
            }
            //if no falses in checkArray then it's true. Otherwise false.
            $this->validity['shape'] = (!in_array(false, $checkArray)) ? true : false;
        }
        if ($this->flags['test']) {
            $fn = $this->function;
            $result = $fn($data, $this->value); // should be bool?
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
