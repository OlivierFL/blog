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
        $this->render('layout/posts.html.twig');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public function show(int $id): void
    {
        $this->render('layout/post.html.twig', [
            'post_title' => 'Post '.$id,
        ]);
    }
}
