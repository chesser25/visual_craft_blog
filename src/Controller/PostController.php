<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private $postService;
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * @Route("/", name="posts")
     */
    public function index(){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $posts = $this->postService->getAllPosts();
        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/post_create", name="post_create")
     */
    public function create(Request $request){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $post = new Post();
        $user = $this->getUser();
        $post->setUser($user);
        $postForm = $this->createForm(PostType::class, $post);
        $postForm->handleRequest($request);
        if($postForm->isSubmitted() && $postForm->isValid()){
            $this->postService->savePost($post);
            return $this->redirectToRoute('posts');
        }
        return $this->render('post/create.html.twig', [
            'form' => $postForm->createView()
        ]);
    }

    /**
     * @Route("/post_edit/{id}", name="post_edit")
     */
    public function edit(Request $request, $id){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $post = $this->postService->getPostById($id);
        $postForm = $this->createForm(PostType::class, $post);
        $postForm->handleRequest($request);
        if($postForm->isSubmitted() && $postForm->isValid()){
            $this->postService->savePost($post);
            return $this->redirectToRoute('posts');
        }
        return $this->render('post/edit.html.twig', [
            'form' => $postForm->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="post_delete")
     */
    public function delete($id){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $post = $this->postService->getPostById($id);
        $this->postService->deletePost($post);
        return $this->redirectToRoute('posts');
    }

    /**
     * @Route("/search", name="posts_search")
     */
    public function search(Request $request){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if(!$request->isMethod('POST')){
            return $this->redirectToRoute('posts');
        }
        $keyword = $request->request->get('search_data');
        $posts = $this->postService->searchPosts($keyword);
        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }
}