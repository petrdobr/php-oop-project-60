<?php

namespace Hexlet\Validator\Types;

class IntType
{
    private $validator;
    private int $id;
    public int $startNumber;
    public int $endNumber;
    //Didn't come up with anything better than flags :(
    public $flags = [
        'required' => false,
        'positive' => false,
        'range' => false
    ];
    public $validity = [
        'required' => false,
        'positive' => false,
        'range' => false
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

    public function isValid($data)
    {
        //Again flags. Will search for better solution later

        if ($this->flags['required']) {
            $this->validity['required'] = (is_int($data)) ? true : false;
        }
        if ($this->flags['positive']) {
            $this->validity['positive'] = ($data > 0) ? true : false;
        }
        if ($this->flags['range']) {
            $this->validity['range'] = ($data > $this->startNumber && $data < $this->endNumber) ? true : false;
        }

        //if no method was called then check for null
        if (!in_array(true, $this->flags)) {
            return $data === null;
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
