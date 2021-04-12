<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class PostValidator
{
    private $validator;
    private $errorsConatiner;
    private $isEntityValid;
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($post){
        $errors = $this->validator->validate($post);
        if(count($errors) > 0){
            $this->isEntityValid = false;
            foreach ($errors as $error){
                $this->errorsConatiner->addError(sprintf('Post: %s. %s', $post->getTitle(), $error->getMessage()));
            }
            return $this;
        }
        $this->isEntityValid = true;
        return $this;
    }

    public function isEntityValid(){
        return $this->isEntityValid;
    }

    public function setErrorsContainer($errorsConatiner){
        $this->errorsConatiner = $errorsConatiner;
        return $this;
    }
}