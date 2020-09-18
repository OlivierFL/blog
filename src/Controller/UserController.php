<?php

namespace App\Controller;

use App\Core\PDOFactory;
use App\Core\Validation\ValidatorFactory;
use App\Managers\UserManager;
use App\Model\User;
use Core\Controller;
use Exception;
use ReflectionException;

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
     * @throws ReflectionException
     * @throws Exception
     */
    public function signup(): void
    {
        if (empty($_POST)) {
            $this->render('layout/signup.html.twig');

            return;
        }

        $db = (new PDOFactory())->getMysqlConnexion();
        $userManager = new UserManager($db);

        $validator = ValidatorFactory::create('sign_up', $_POST, $userManager);

        if ($validator->isValid()) {
            $user = new User($_POST);
            $user->setCreatedAt((new \DateTime())->format('Y-m-d H:i:s'));
            $user->setUpdatedAt((new \DateTime())->format('Y-m-d H:i:s'));
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));

            $result = $userManager->create($user);

            if (false === $result) {
                throw new Exception('Erreur lors de la création de l\'utilisateur');
            }

            $this->render('layout/index.html.twig', [
                'signup_success' => 'Félicitations ! Vous êtes désormais inscrit et vous pouvez dès à présent poster des commentaires',
            ]);

            return;
        }

        $this->render('layout/signup.html.twig', [
            'errors' => $validator->getErrors(),
        ]);
    }
}
