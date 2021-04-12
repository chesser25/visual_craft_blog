<?php

namespace App\Service;

class ErrorsContainer
{
    private $errors;
    public function __construct()
    {
        $this->errors = [];
    }

    public function addError($error){
        array_push($this->errors, $error);
    }

    public function getErrors(){
        return $this->errors;
    }
}