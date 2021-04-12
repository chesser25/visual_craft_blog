<?php

namespace App\Service;

class CsvParser
{
    private $data;
    private $keys;

    public function parse($csvFilePath){
        $csv = new \ParseCsv\Csv();
        $csv->delimiter = "\t";
        $csv->parse($csvFilePath);
        $this->keys = $csv->titles;
        $this->data = $csv->data;
    }

    public function getKeys(){
        return $this->keys;
    }

    public function getData(){
        return $this->data;
    }
}