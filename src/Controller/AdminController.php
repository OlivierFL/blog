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

    public function showComment()
    {
        return $this->render('admin/comment.html.twig');
    }

    public function editComment()
    {
        return $this->render('admin/comment_edit.html.twig');
    }

    public function showUser()
    {
        return $this->render('admin/user.html.twig');
    }

    public function editUser()
    {
        return $this->render('admin/user_edit.html.twig');
    }
}
