<?php

namespace App\Controller;

use Core\Controller;
use Exception;

class PostsController extends Controller
{
    /**
     * @throws Exception
     */
    public function list()
    {
        return $this->render('layout/posts.html.twig');
    }

    /**
     * @throws Exception
     */
    public function show(string $id)
    {
        return $this->render('layout/post.html.twig', [
            'post_title' => 'Post '.$id,
        ]);
    }
}
