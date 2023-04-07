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
        $validity = [
            'required' => false,
            'contains' => false,
            'minLength' => false 
        ];

        if ($this->flags['required']) {
            $validity['required'] = (is_string($data)) ? true : false;
        } 
        if ($this->flags['contains']) {
            $validity['contains'] = (str_contains($data, $this->subLine)) ? true : false;
        } 
        if ($this->flags['minLength']) {
            $validity['minLength'] = ($this->length <= strlen($data)) ? true : false;
        }

        //if no method was called then check for null
        if (!in_array(true, $this->flags)) {
            return $data === null;
        }

        //check each validity flag and compare with methods flag, if they are not coincident then false
        //otherwise - true
        foreach($validity as $option => $flag) {
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
