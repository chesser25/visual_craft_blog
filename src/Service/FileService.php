<?php

namespace App\Service;

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileService
{
    private $uploadsDir;
    private $slugger;

    public function __construct($uploadsDir, SluggerInterface $slugger)
    {
        $this->uploadsDir = $uploadsDir;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->uploadsDir, $fileName);
            return $fileName;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function remove($filename){
        $filePath = $this->getFilePath($filename);
        if(file_exists($filePath)){
            unlink($filePath);
        }
    }

    public function getFilePath($filename){
        return $this->uploadsDir . $filename;
    }
}
