<?php

namespace App\Controller;

use App\Core\PDOFactory;
use App\Core\Validation\ValidatorFactory;
use App\Managers\AdminManager;
use App\Managers\UserManager;
use App\Model\User;
use Core\Controller;
use Exception;
use PDO;
use ReflectionException;

class AdminController extends Controller
{
    private const USER_EDIT_TEMPLATE = 'admin/user_edit.html.twig';
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
     * @throws ReflectionException
     * @throws Exception
     */
    public function editUser(int $id): void
    {
        $user = $this->getUser($id);
        if (empty($_POST)) {
            $this->render(self::USER_EDIT_TEMPLATE, [
                'user' => $user,
            ]);

            return;
        }

        $validator = ValidatorFactory::create('user_edit', $_POST, $this->userManager);

        if ($validator->isValid()) {
            $result = $this->updateUser($user);

            if (false === $result) {
                throw new Exception('Erreur lors de la mise à jour de l\'utilisateur');
            }

            $this->render(self::USER_EDIT_TEMPLATE, [
                'user' => $this->getUser($id),
                'success' => 'Utilisateur mis à jour',
            ]);

            return;
        }

        $this->render(self::USER_EDIT_TEMPLATE, [
            'user' => $this->getUser($id),
            'errors' => $validator->getErrors(),
        ]);
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

    /**
     * @param array $user
     *
     * @throws ReflectionException
     *
     * @return int
     */
    private function updateUser(array $user): int
    {
        foreach ($_POST as $key => $value) {
            if ($value &&
                (\array_key_exists($key, $user['base_infos']) && $value !== $user['base_infos'][$key])
            ) {
                $user['base_infos'][$key] = $value;
            }
        }
        $updatedUser = new User($user['base_infos']);
        $updatedUser->setUpdatedAt((new \DateTime())->format('Y-m-d H:i:s'));

        return $this->userManager->update($updatedUser);
    }
}
