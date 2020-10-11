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
            throw new Exception('Accès non autorisé !');
        }
    }

    /**
     * @throws Exception
     */
    public function index(): void
    {
        $users = $this->userManager->findBy([], ['created_at' => 'DESC'], 3);
        $posts = $this->postManager->findBy([], ['created_at' => 'DESC'], 2);

        $this->render('admin/index.html.twig', [
            'users' => $users,
            'posts' => $posts,
        ]);
    }

    /**
     * @throws Exception
     */
    public function readPosts(): void
    {
        $posts = $this->postManager->findAllWithAuthor();

        $this->render('admin/posts.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public function readPost(int $id): void
    {
        $post = $this->postManager->findOneWithAuthor($id);

        $this->render('admin/post.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @throws Exception
     */
    public function createPost(): void
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $result = $this->postAdministrator->createPost($_POST);
        }

        $this->render('admin/post_create.html.twig', [
            'messages' => $result ?? null,
        ]);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public function updatePost(int $id): void
    {
        $post = $this->postManager->findOneWithAuthor($id);
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $result = $this->postAdministrator->updatePost($post, $_POST);
        }

        $this->render('admin/post_edit.html.twig', [
            'post' => $this->postManager->findOneWithAuthor($id),
            'messages' => $result ?? null,
        ]);
    }

    /**
     * @throws Exception
     */
    public function deletePost(): void
    {
        $this->render('admin/post_edit.html.twig');
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
    public function deleteUser(): void
    {
        $user = $this->userAdministrator->getUser($_POST['id']);

        try {
            $this->userAdministrator->deleteUser($user);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la suppression de l\'utilisateur (id:'.$user['base_infos']['id'].') : '.$e->getMessage());
        }

        $this->render('admin/successful_edit.html.twig', [
            'messages' => ['Utilisateur supprimé'],
            'link' => 'users',
            'link_text' => 'utilisateurs',
        ]);
    }
}
