<?php

namespace App\Controller;

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
            try {
                $userId = $this->auth->authenticateUser($_POST);
                $user = $this->userAdministrator->getUser($userId);
                $this->session->set('user', $user);
                header('Location: /');
            } catch (Exception $e) {
                $messages[] = $e->getMessage();
            }
        }

        $this->render('layout/login.html.twig', [
            'user' => $user ?? null,
            'messages' => $messages ?? null,
        ]);
    }

    /**
     * @throws Exception
     */
    public function signup(): void
    {
        if ('POST' === $_SERVER['REQUEST_METHOD'] && !empty($_POST)) {
            $result = $this->userAdministrator->createUser($_POST);
        }

        $this->render('layout/signup.html.twig', [
            'messages' => $result ?? null,
        ]);
    }

    public function logout(): void
    {
        $this->session->stop();
        header('Location: /user/login');
    }
}
