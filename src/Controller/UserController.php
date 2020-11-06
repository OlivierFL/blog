<?php

namespace App\Controller;

use App\Exceptions\InvalidPasswordException;
use App\Exceptions\TwigException;
use App\Exceptions\UserNotFoundException;
use Core\Controller;
use Exception;

class UserController extends Controller
{
    /**
     * @throws TwigException
     * @throws Exception
     */
    public function login(): void
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            try {
                $this->auth->authenticateUser($_POST);
                $this->addMessage('Connexion réussie');
                header('Location: /');
            } catch (UserNotFoundException | InvalidPasswordException $e) {
                $this->addMessage($e->getMessage());
            }
        }

        $this->render('layout/login.html.twig');
    }

    /**
     * @throws Exception
     */
    public function signup(): void
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $result = $this->userAdministrator->createUser($_POST);
            $this->addMessage($result);
        }

        $this->render('layout/signup.html.twig');
    }

    public function logout(): void
    {
        $this->session->remove('current_user');
        $this->addMessage('Vous êtes maintenant déconnecté');
        header('Location: /user/login');
    }
}
