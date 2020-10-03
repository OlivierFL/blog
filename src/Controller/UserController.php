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
        $messages = [];
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $user = $this->authenticateUser();
        }

        $this->render('layout/login.html.twig', [
            'user' => $user,
            'messages' => $messages ?? null,
        ]);
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

    private function authenticateUser()
    {
        $messages = [];
        $validator = ValidatorFactory::create(ValidatorFactory::LOGIN, $_POST, $this->userManager);

        if (!$validator->isValid()) {
            return $validator->getErrors();
        }

        $data = $this->userManager->findOneBy(['email' => $_POST['email']]);

        if (empty($data)) {
            $messages[] = 'Aucun utilisateur trouvé pour l\'adresse email : '.$_POST['email'];
            return $messages;
        }

        if (false === $this->checkPassword($_POST['password'], $data['password'])) {
            $messages[] = 'Mot de passe invalide, veuillez réessayer.';
            return $messages;
        }

        return $this->getUser($data['id']);
    }

    /**
     * @param string $password
     * @param string $hash
     *
     * @return bool
     */
    private function checkPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
