<?php

namespace App\Controller;

use App\Core\Validation\ValidatorFactory;
use App\Model\User;
use Core\Controller;
use Exception;
use ReflectionException;

class AdminController extends Controller
{
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
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $validator = ValidatorFactory::create('user_edit', $_POST, $this->userManager);

            if ($validator->isValid()) {
                $result = $this->updateUser($user);

                if (false === $result) {
                    throw new Exception('Erreur lors de la mise à jour de l\'utilisateur');
                }
                $messages[] = 'Utilisateur mis à jour';
            } else {
                $messages = $validator->getErrors();
            }
        }

        $this->render('admin/user_edit.html.twig', [
            'user' => $this->getUser($id),
            'messages' => $messages ?? null,
        ]);
    }

    /**
     * @param $user
     *
     * @throws ReflectionException
     *
     * @return int
     */
    private function updateUser($user): int
    {
        $updatedUser = (new User($user['base_infos']))
            ->setUserName($_POST['user_name'])
            ->setFirstName($_POST['first_name'])
            ->setLastName($_POST['last_name'])
            ->setEmail($_POST['email'])
            ->setRole($_POST['role'])
        ;
        $updatedUser->setUpdatedAt((new \DateTime())->format('Y-m-d H:i:s'));

        return $this->userManager->update($updatedUser);
    }
}
