<?php

namespace Hexlet\Validator\Types;

use PHPUnit\Framework\Constraint\Callback;

class StringType
{
    private $validator;
    private string $subLine;
    private int $id;
    public int $length;
    private $function;
    private $value;
    //Didn't come up with anything better than flags :(
    public $flags = [
        'required' => false,
        'contains' => false,
        'minLength' => false,
        'test' => false
    ];
    public $validity = [
        'required' => false,
        'contains' => false,
        'minLength' => false,
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

    public function contains(string $subLine)
    {
        $this->flags['contains'] = true;
        $this->subLine = $subLine;
        return $this;
    }

    public function minLength(int $length)
    {
        $this->flags['minLength'] = true;
        $this->length = $length;
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
            $this->validity['required'] = (is_string($data) && $data != null) ? true : false;
        }
        if ($this->flags['contains']) {
            $this->validity['contains'] = (str_contains($data, $this->subLine)) ? true : false;
        }
        if ($this->flags['minLength']) {
            $this->validity['minLength'] = ($this->length <= strlen($data)) ? true : false;
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
