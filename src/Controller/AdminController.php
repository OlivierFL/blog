<?php

namespace App\Controller;

use App\Core\PDOFactory;
use App\Managers\UserManager;
use App\Model\User;
use Core\Controller;
use Exception;
use PDO;
use ReflectionException;

class AdminController extends Controller
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var PDO
     */
    private $db;

    /**
     * AdminController constructor.
     *
     * @throws ReflectionException
     */
    public function __construct()
    {
        $this->db = (new PDOFactory())->getMysqlConnexion();
        $this->userManager = new UserManager($this->db);
        Controller::__construct();
    }

    /**
     * @throws Exception
     */
    public function index(): void
    {
        $users = $this->userManager->getLastThreeUsers();

        $lastThreeUsers = [];
        foreach ($users as $user) {
            $lastThreeUsers[] = new User($user);
        }

        $this->render('admin/index.html.twig', [
            'users' => $lastThreeUsers,
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
     * @throws Exception
     */
    public function listUsers(): void
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
