<?php

namespace App\Controller;

use Core\Controller;
use Exception;
use ReflectionException;

class AdminController extends Controller
{
    /**
     * AdminController constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        if ('admin' !== $this->auth->getCurrentUserRole()) {
            $this->unauthorizedAccess();
        }
    }

    /**
     * @throws Exception
     */
    public function index(): void
    {
        $users = $this->userManager->findBy([], ['created_at' => 'DESC'], 3);

        $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
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
    public function readUsers(): void
    {
        $users = $this->userManager->findAll();

        $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public function readUser(int $id): void
    {
        $user = $this->userAdministrator->getUser($id);

        $this->render('admin/user.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @param int $id
     *
     * @throws ReflectionException
     * @throws Exception
     */
    public function updateUser(int $id): void
    {
        $user = $this->userAdministrator->getUser($id);
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $result = $this->userAdministrator->updateUser($user, $_POST);
        }

        $this->render('admin/user_edit.html.twig', [
            'user' => $this->userAdministrator->getUser($id),
            'messages' => $result ?? null,
        ]);
    }

    /**
     * @throws Exception
     */
    private function unauthorizedAccess(): void
    {
        $this->render('layout/forbidden.html.twig');
        exit();
    }
}
