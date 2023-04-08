<?php

namespace Hexlet\Validator\Types;

use Hexlet\Validator\Validator;

class StringType
{
    private Validator $validator;
    private string $subLine;
    private int $id;
    private int $length;
    private mixed $function;
    private mixed $value;
    //Didn't come up with anything better than flags :(
    private array $flags = [
        'required' => false,
        'contains' => false,
        'minLength' => false,
        'test' => false
    ];
    private array $validity = [
        'required' => false,
        'contains' => false,
        'minLength' => false,
        'test' => false
    ];

    public function __construct(Validator $validator, int $id)
    {
        $this->id = $id;
        $this->validator = $validator;
    }

    public function required(): StringType
    {
        $this->flags['required'] = true;
        return $this;
    }

    public function contains(string $subLine): StringType
    {
        $this->flags['contains'] = true;
        $this->subLine = $subLine;
        return $this;
    }

    public function minLength(int $length): StringType
    {
        $this->flags['minLength'] = true;
        $this->length = $length;
        return $this;
    }
    public function test(string $name, mixed $value): StringType
    {
        $this->function = $this->validator->getFunctionByName($name);
        $this->value = $value;
        $this->flags['test'] = true;
        return $this;
    }

    public function isValid(mixed $data): bool
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
