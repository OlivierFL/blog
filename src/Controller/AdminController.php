<?php

namespace App\Controller;

use Core\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }

    public function listPosts()
    {
        return $this->render('admin/posts.html.twig');
    }

    public function listComments()
    {
        return $this->render('admin/comments.html.twig');
    }

    public function listUsers()
    {
        return $this->render('admin/users.html.twig');
    }

    public function showPost(int $id)
    {
        return $this->render('admin/post.html.twig', [
            'post_title' => 'Post '.$id,
        ]);
    }

    public function editPost(int $id)
    {
        return $this->render('admin/post_edit.html.twig', [
            'post_title' => 'Post'.$id,
        ]);
    }
}
