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
                $this->auth->authenticateUser($_POST);
                header('Location: /');
            } catch (Exception $e) {
                $messages[] = $e->getMessage();
            }
        }

        $this->render('layout/login.html.twig', [
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
        $this->session->remove('current_user');
        header('Location: /user/login');
    }
}
