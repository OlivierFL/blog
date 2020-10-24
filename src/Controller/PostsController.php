<?php

namespace App\Controller;

use Core\Controller;
use Exception;

class PostsController extends Controller
{
    /**
     * @throws Exception
     */
    public function list(): void
    {
        $posts = $this->postManager->findAllWithAuthor();

        $this->render('layout/posts.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @param string $slug
     *
     * @throws Exception
     */
    public function show(string $slug): void
    {
        $post = $this->postManager->findOneWithAuthorBySlug($slug);

        $this->render('layout/post.html.twig', [
            'post' => $post,
        ]);
    }
}
