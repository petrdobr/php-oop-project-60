<?php

namespace Hexlet\Validator\Types;

class StringType
{
    private $validator;
    private string $subLine;
    private int $id;
    public int $length;
    //Didn't come up with anything better than flags :(
    public $flags = [
        'required' => false,
        'contains' => false,
        'minLength' => false
    ];
    public $validity = [
        'required' => false,
        'contains' => false,
        'minLength' => false
    ];

    public function __construct($validator, $id)
    {
        $this->validator = $validator;
        $this->id = $id;
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

    public function isValid($data)
    {
        //Again flags. Will search for better solution later

        if ($this->flags['required']) {
            $this->validity['required'] = (is_string($data) && $data != null) ? true : false;
        }
        if ($this->flags['contains']) {
            $this->validity['contains'] = (str_contains($data, $this->subLine)) ? true : false;
        }
        if ($this->flags['minLength']) {
            $this->validity['minLength'] = ($this->length <= strlen($data)) ? true : false;
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
