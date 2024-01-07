<?php

namespace Hexlet\Validator\Types;

use Hexlet\Validator\Validator;

class NumberType
{
    private Validator $validator;
    private int $id;
    private int $beginNumber;
    private int $endNumber;
    private mixed $function;
    private mixed $value;
    //Didn't come up with anything better than flags :(
    private array $flags = [
        'required' => false,
        'positive' => false,
        'range' => false,
        'test' => false
    ];
    private array $validity = [
        'required' => false,
        'positive' => false,
        'range' => false,
        'test' => false
    ];

    public function __construct(Validator $validator, int $id)
    {
        $this->id = $id;
        $this->validator = $validator;
    }

    public function required(): NumberType
    {
        $this->flags['required'] = true;
        return $this;
    }

    public function positive(): NumberType
    {
        $this->flags['positive'] = true;
        return $this;
    }

    public function range(int $startNumber, int $endNumber): NumberType
    {
        $this->flags['range'] = true;
        $this->beginNumber = $startNumber;
        $this->endNumber = $endNumber;
        return $this;
    }
    public function test(string $name, mixed $value): NumberType
    {
        $this->function = $this->validator->getFunctionByName($name);
        $this->value = $value;
        $this->flags['test'] = true;
        return $this;
    }

    public function isValid(mixed $data): bool
    {
        if ($this->flags['required']) {
            $this->validity['required'] = (is_int($data) && $data !== null) ? true : false;
        }
        if ($this->flags['positive']) {
            $this->validity['positive'] = ($data >= 0) ? true : false;
        }
        if ($this->flags['range']) {
            $this->validity['range'] = ($data > $this->beginNumber && $data < $this->endNumber) ? true : false;
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
