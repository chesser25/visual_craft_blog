<?php

namespace App\Service;

use App\Constant\MainConstant;
use App\Entity\Category;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

class PostImporter
{
    private $entityManager;
    private $fileService;
    private $csvParser;
    private $csvPostFileValidator;
    private $postValidator;
    private $errorsConatiner;
    private $csvFilename;
    public function __construct(EntityManagerInterface $entityManager, FileService $fileService, CsvParser $csvParser,
                                CsvPostFileValidator $csvPostFileValidator, PostValidator $postValidator, ErrorsContainer $errorsContainer)
    {
        $this->entityManager = $entityManager;
        $this->fileService = $fileService;
        $this->csvParser = $csvParser;
        $this->csvPostFileValidator = $csvPostFileValidator;
        $this->postValidator = $postValidator;
        $this->errorsConatiner = $errorsContainer;
    }

    // Import data from csv file, create posts and save it
    public function importPosts($file, $currentUser){

        // Try to upload csv file
        $this->csvFilename = $this->fileService->upload($file);
        if(!$this->csvFilename){
            $this->errorsConatiner->addError('Some issure has occured when uploading csv file.');
            return;
        }

        // Parse csv file
        $csvFilePath = $this->fileService->getFilePath($this->csvFilename);
        $this->csvParser->parse($csvFilePath);

        // Validate csv file
        $csvKeys = $this->csvParser->getKeys();
        $isCsvValid = $this->csvPostFileValidator->validate($csvKeys)->isCsvValid();
        if(!$isCsvValid){
            $this->errorsConatiner->addError('CSV file is not valid.');
            return;
        }

        // Try to save csv data as posts objects
        $data = $this->csvParser->getData();
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        foreach ($data as $datum){
            $post = new Post();
            $category = $categoryRepository->findOneBy(['name' => $datum[MainConstant::CATEGORY_KEY]]);
            $post->setUser($currentUser)->setTitle($datum[MainConstant::TITLE_KEY])->setBody($datum[MainConstant::BODY_KEY])->setCategory($category);
            $isEntityValid = $this->postValidator->setErrorsContainer($this->errorsConatiner)->validate($post)->isEntityValid();
            if($isEntityValid){
                $this->entityManager->persist($post);
            }
        }
        $this->entityManager->flush();
        return true;
    }

    public function getErrors(){
        return $this->errorsConatiner->getErrors();
    }

    public function getCsvFileName(){
        return $this->csvFilename;
    }
}