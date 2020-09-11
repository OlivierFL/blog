<?php

namespace App\Controller;

use App\Core\PDOFactory;
use App\Managers\AdminManager;
use App\Managers\UserManager;
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
     * @var AdminManager
     */
    private $adminManager;
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
        $this->adminManager = new AdminManager($this->db);
        Controller::__construct();
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
     * @param int $id
     *
     * @throws Exception
     */
    public function showUser(int $id): void
    {
        $user = $this->getUser($id);

        $this->render('admin/user.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public function editUser(int $id): void
    {
        if (empty($_POST)) {
            $user = $this->getUser($id);

            $this->render('admin/user_edit.html.twig', [
                'user' => $user,
            ]);

            return;
        }

        $this->render('admin/user_edit.html.twig');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     *
     * @return array
     */
    private function getUser(int $id): array
    {
        $userInfos = $this->userManager->findOneBy(['id' => $id]);

        if ('admin' === $userInfos['role']) {
            $adminInfos = $this->adminManager->findOneBy(['user_id' => $id]);
        }

        return array_combine(['base_infos', 'admin_infos'], [$userInfos, $adminInfos ?? null]);
    }
}
