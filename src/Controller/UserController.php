<?php

namespace App\Controller;

use App\Core\Validation\ValidatorFactory;
use App\Model\User;
use Core\Controller;
use Exception;

class UserController extends Controller
{
    /**
     * @throws Exception
     */
    public function login(): void
    {
        $this->render('layout/login.html.twig');
    }

    /**
     * @throws Exception
     */
    public function signup(): void
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $result = $this->createUser($_POST);
        }

        $this->render('layout/signup.html.twig', [
            'messages' => $result ?? null,
        ]);
    }

    /**
     * @param array $data
     *
     * @throws Exception
     *
     * @return array
     */
    private function createUser($data): array
    {
        $validator = ValidatorFactory::create(ValidatorFactory::SIGN_UP, $_POST, $this->userManager);

        if ($validator->isValid()) {
            $user = new User($data);
            $user->setCreatedAt((new \DateTime())->format('Y-m-d H:i:s'));
            $user->setUpdatedAt((new \DateTime())->format('Y-m-d H:i:s'));
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));

            $result = $this->userManager->create($user);

            if (false === $result) {
                throw new Exception('Erreur lors de la création de l\'utilisateur');
            }

            $messages[] = 'Félicitations ! Vous êtes désormais inscrit et vous pouvez dès à présent poster des commentaires';
        } else {
            $messages = $validator->getErrors();
        }

        return $messages;
    }
}
