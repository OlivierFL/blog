<?php

namespace App\Controller;

use Core\Controller;
use Exception;

class AdminController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(): void
    {
        $this->render('admin/index.html.twig');
    }

    /**
     * @throws Exception
     */
    public function listPosts(): void
    {
        $this->render('admin/posts.html.twig');
    }

    /**
     * @throws Exception
     */
    public function listComments(): void
    {
        $this->render('admin/comments.html.twig');
    }

    /**
     * @throws Exception
     */
    public function listUsers(): void
    {
        $this->render('admin/users.html.twig');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public function showPost(int $id): void
    {
        $this->render('admin/post.html.twig', [
            'post_title' => 'Post '.$id,
        ]);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public function editPost(int $id): void
    {
        $this->render('admin/post_edit.html.twig', [
            'post_title' => 'Post'.$id,
        ]);
    }

    /**
     * @throws Exception
     */
    public function showComment(): void
    {
        $this->render('admin/comment.html.twig');
    }

    /**
     * @throws Exception
     */
    public function editComment(): void
    {
        $this->render('admin/comment_edit.html.twig');
    }

    /**
     * @throws Exception
     */
    public function showUser(): void
    {
        $this->render('admin/user.html.twig');
    }

    /**
     * @throws Exception
     */
    public function editUser(): void
    {
        $this->render('admin/user_edit.html.twig');
    }
}
