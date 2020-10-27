<?php

namespace App\Controller;

use App\Exceptions\TwigException;
use Core\Controller;

class PostsController extends Controller
{
    /**
     * @throws TwigException
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
     * @throws TwigException
     */
    public function show(string $slug): void
    {
        $post = $this->postManager->findOneWithAuthorBySlug($slug);

        $this->render('layout/post.html.twig', [
            'post' => $post,
        ]);
    }
}
