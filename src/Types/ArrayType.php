<?php

namespace Hexlet\Validator\Types;

use Hexlet\Validator\Validator;

class ArrayType
{
    private Validator $validator;
    private int $id;
    private int $checkSize;
    private mixed $function;
    private mixed $value;
    //Didn't come up with anything better than flags :(
    private array $flags = [
        'required' => false,
        'sizeof' => false,
        'shape' => false,
        'test' => false
    ];
    private array $validity = [
        'required' => false,
        'sizeof' => false,
        'shape' => false,
        'test' => false
    ];
    private array $shapeArray;

    public function __construct(Validator $validator, int $id)
    {
        $this->id = $id;
        $this->validator = $validator;
    }

    public function required(): ArrayType
    {
        $this->flags['required'] = true;
        return $this;
    }

    public function sizeof(int $size): ArrayType
    {
        $this->flags['sizeof'] = true;
        $this->checkSize = $size;
        return $this;
    }

    public function shape(array $array): ArrayType
    {
        $this->flags['shape'] = true;
        $this->shapeArray = $array;
        return $this;
    }

    public function test(string $name, mixed $value): ArrayType
    {
        $this->function = $this->validator->getFunctionByName($name);
        $this->value = $value;
        $this->flags['test'] = true;
        return $this;
    }

    public function isValid(mixed $data): bool
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
            $this->validity['shape'] = (!in_array(false, $checkArray, false)) ? true : false;
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

    public function getId(): int
    {
        return $this->id;
    }
}
