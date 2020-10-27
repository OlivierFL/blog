<?php

namespace App\Controller;

use App\Exceptions\AccessDeniedException;
use App\Exceptions\DatabaseException;
use App\Exceptions\FileUploadException;
use App\Exceptions\PostException;
use App\Exceptions\TwigException;
use Core\Controller;
use Exception;
use ReflectionException;

class AdminController extends Controller
{
    /**
     * @throws AccessDeniedException
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();
        if ('admin' !== $this->auth->getCurrentUserRole()) {
            throw new AccessDeniedException('Accès non autorisé !');
        }
    }

    /**
     * @throws TwigException
     * @throws DatabaseException
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
     * @throws TwigException
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
     * @throws TwigException
     */
    public function readPost(int $id): void
    {
        $post = $this->postManager->findOneWithAuthor($id);

        $this->render('admin/post.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @throws TwigException
     * @throws Exception
     */
    public function createPost(): void
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $result = $this->postAdministrator->createPost($_POST);
            $this->addMessage($result);
        }

        $this->render('admin/post_create.html.twig', [
            'link' => 'posts',
            'link_text' => 'articles',
        ]);
    }

    /**
     * @param int $id
     *
     * @throws DatabaseException
     * @throws ReflectionException
     * @throws TwigException
     * @throws FileUploadException
     * @throws PostException
     */
    public function updatePost(int $id): void
    {
        $post = $this->postManager->findOneWithAuthor($id);
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $result = $this->postAdministrator->updatePost($post, $_POST);
            $this->addMessage($result);
        }

        $this->render('admin/post_edit.html.twig', [
            'post' => $this->postManager->findOneWithAuthor($id),
            'link' => 'posts',
            'link_text' => 'articles',
        ]);
    }

    /**
     * @throws PostException
     * @throws TwigException
     * @throws Exception
     */
    public function deletePost(): void
    {
        $post = $this->postManager->findOneBy(['id' => $_POST['id']]);

        try {
            $this->postAdministrator->deletePost($post);
            $this->addMessage('Article supprimé');
        } catch (Exception $e) {
            throw PostException::delete($post['id']);
        }

        $this->render('admin/successful_edit.html.twig', [
            'link' => 'posts',
            'link_text' => 'articles',
        ]);
    }

    /**
     * @throws TwigException
     */
    public function listComments(): void
    {
        $this->render('admin/comments.html.twig');
    }

    /**
     * @throws TwigException
     */
    public function showComment(): void
    {
        $this->render('admin/comment.html.twig');
    }

    /**
     * @throws TwigException
     */
    public function editComment(): void
    {
        $this->render('admin/comment_edit.html.twig');
    }

    /**
     * @throws TwigException
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
     * @throws TwigException
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
     * @throws TwigException
     * @throws Exception
     */
    public function updateUser(int $id): void
    {
        $user = $this->userAdministrator->getUser($id);
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $result = $this->userAdministrator->updateUser($user, $_POST);
            $this->addMessage($result);
        }

        $this->render('admin/user_edit.html.twig', [
            'user' => $this->userAdministrator->getUser($id),
            'link' => 'users',
            'link_text' => 'utilisateurs',
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
            $this->addMessage('Utilisateur supprimé');
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la suppression de l\'utilisateur (id:'.$user['base_infos']['id'].') : '.$e->getMessage());
        }

        $this->render('admin/successful_edit.html.twig', [
            'link' => 'users',
            'link_text' => 'utilisateurs',
        ]);
    }
}
