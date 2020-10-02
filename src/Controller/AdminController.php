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
    public function readUser(int $id): void
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
    public function updateUser(int $id): void
    {
        $user = $this->getUser($id);
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $result = $this->updateOldUser($user);
        }

        $this->render('admin/user_edit.html.twig', [
            'user' => $this->getUser($id),
            'messages' => $result ?? null,
        ]);
    }

    /**
     * @param $user
     *
     * @throws ReflectionException
     * @throws Exception
     *
     * @return array
     */
    private function updateOldUser($user): array
    {
        $validator = ValidatorFactory::create(ValidatorFactory::UPDATE_USER, $_POST, $this->userManager);

        if ($validator->isValid()) {
            $updatedUser = (new User($user['base_infos']))
                ->setUserName($_POST['user_name'])
                ->setFirstName($_POST['first_name'])
                ->setLastName($_POST['last_name'])
                ->setEmail($_POST['email'])
                ->setRole($_POST['role'])
        ;
            $updatedUser->setUpdatedAt((new \DateTime())->format('Y-m-d H:i:s'));

            $result = $this->userManager->update($updatedUser);

            if (false === $result) {
                throw new Exception('Erreur lors de la mise à jour de l\'utilisateur');
            }
            $messages[] = 'Utilisateur mis à jour';
        } else {
            $messages = $validator->getErrors();
        }

        return $messages;
    }
}
