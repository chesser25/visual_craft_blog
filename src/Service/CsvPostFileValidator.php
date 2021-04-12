<?php

namespace App\Service;

use App\Constant\MainConstant;

class CsvPostFileValidator
{
    private $isValid = false;
    const KEYS_TO_COMPARE = [
        MainConstant::TITLE_KEY,
        MainConstant::BODY_KEY,
        MainConstant::CATEGORY_KEY
    ];

    public function validate($csvKeys){
        foreach (self::KEYS_TO_COMPARE as $key){
            if(!in_array($key, $csvKeys)){
                return $this;
            }
        }
        $this->isValid = true;
        return $this;
    }

    public function isCsvValid(){
        return $this->isValid;
    }
}