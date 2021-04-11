<?php

namespace App\Service;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;

class PostService
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllPosts(){
        $repository = $this->entityManager->getRepository(Post::class);
        return $repository->findBy([], ['id' => 'DESC']);
    }

    public function savePost($post){
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }

    public function getPostById($id){
        $repository = $this->entityManager->getRepository(Post::class);
        return $repository->findOneBy(['id' => $id]);
    }

    public function deletePost($post){
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

    public function searchPosts($keyword){
        $repository = $this->entityManager->getRepository(Post::class);
        return $repository->findPostsByKeyword($keyword);
    }
}