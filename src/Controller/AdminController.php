<?php

namespace App\Controller;

use App\Model\Comment;
use App\Exceptions\AccessDeniedException;
use App\Exceptions\DatabaseException;
use App\Exceptions\FileUploadException;
use App\Exceptions\PostException;
use App\Exceptions\TwigException;
use App\Exceptions\UserException;
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
        $comments = $this->commentManager->findBy(['status' => Comment::STATUS_PENDING], ['created_at' => 'DESC'], 2);

        $this->render('admin/index.html.twig', [
            'users' => $users,
            'posts' => $posts,
            'comments' => $comments,
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
     * @throws DatabaseException
     * @throws FileUploadException
     * @throws PostException
     * @throws ReflectionException
     * @throws TwigException
     */
    public function createPost(): void
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $this->postAdministrator->createPost($_POST);
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
            $this->postAdministrator->updatePost($post, $_POST);
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
    public function readComments(): void
    {
        $comments = $this->commentManager->findAllWithAuthor();

        $this->render('admin/comments.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     * @throws TwigException
     */
    public function readComment(int $id): void
    {
        $comment = $this->commentManager->findOneWithAuthor($id);

        $this->render('admin/comment.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @throws TwigException
     * @param $id
     *
     * @throws Exception
     */
    public function updateComment(int $id): void
    {
        $comment = $this->commentManager->findOneWithAuthor($id);
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $this->commentAdministrator->updateComment(new Comment($comment), $_POST);
        }

        $this->render('admin/comment.html.twig', [
            'comment' => $this->commentManager->findOneWithAuthor($id),
            'link' => 'comments',
            'link_text' => 'commentaires',
        ]);
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
            $this->userAdministrator->updateUser($user, $_POST);
        }

        $this->render('admin/user_edit.html.twig', [
            'user' => $this->userAdministrator->getUser($id),
            'link' => 'users',
            'link_text' => 'utilisateurs',
        ]);
    }

    /**
     * @throws TwigException
     * @throws UserException
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
        $this->userAdministrator->deleteUser($user);
        $this->addMessage('Utilisateur supprimé');

        $this->render('admin/successful_edit.html.twig', [
            'link' => 'users',
            'link_text' => 'utilisateurs',
        ]);
    }
}
