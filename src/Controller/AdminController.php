<?php

namespace App\Controller;

use App\Exceptions\AccessDeniedException;
use App\Exceptions\DatabaseException;
use App\Exceptions\FileUploadException;
use App\Exceptions\PostException;
use App\Exceptions\TwigException;
use App\Exceptions\UserException;
use App\Managers\CommentManager;
use App\Managers\PostManager;
use App\Managers\UserManager;
use App\Model\Comment;
use App\Service\CommentAdministrator;
use App\Service\PostAdministrator;
use App\Service\UserAdministrator;
use Core\Controller;
use Exception;
use ReflectionException;

class AdminController extends Controller
{
    /**
     * @var CommentAdministrator
     */
    protected CommentAdministrator $commentAdministrator;
    /**
     * @var CommentManager
     */
    protected CommentManager $commentManager;
    /**
     * @var UserManager
     */
    private UserManager $userManager;
    /**
     * @var PostAdministrator
     */
    private PostAdministrator $postAdministrator;
    /**
     * @var PostManager
     */
    private PostManager $postManager;
    /**
     * @var UserAdministrator
     */
    private UserAdministrator $userAdministrator;

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
        $this->userManager = new UserManager();
        $this->userAdministrator = new UserAdministrator($this->session);
        $this->postManager = new PostManager();
        $this->postAdministrator = new PostAdministrator($this->session);
        $this->commentManager = new CommentManager();
        $this->commentAdministrator = new CommentAdministrator($this->session);
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
     * @throws Exception
     */
    public function updatePost(int $id): void
    {
        $post = $this->postManager->findOneBy(['id' => $id]);
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
     * @throws TwigException
     * @throws Exception
     */
    public function deletePost(): void
    {
        $post = $this->postManager->findOneBy(['id' => $_POST['id']]);

        $this->postAdministrator->deletePost($post);

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
     * @param $id
     *
     * @throws TwigException
     * @throws Exception
     */
    public function updateComment(int $id): void
    {
        $comment = $this->commentManager->findOneBy(['id' => $id]);
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $this->commentAdministrator->updateComment($comment, $_POST);
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
        $user = $this->userManager->findUser($id);

        $this->render('admin/user.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @param int $id
     *
     * @throws TwigException
     * @throws Exception
     */
    public function updateUser(int $id): void
    {
        $user = $this->userManager->findUser($id);
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $this->userAdministrator->updateUser($user, $_POST);
        }

        $this->render('admin/user_edit.html.twig', [
            'user' => $this->userManager->findUser($id),
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
        $user = $this->userManager->findUser($_POST['id']);

        $this->userAdministrator->deleteUser($user);

        $this->render('admin/successful_edit.html.twig', [
            'link' => 'users',
            'link_text' => 'utilisateurs',
        ]);
    }
}
