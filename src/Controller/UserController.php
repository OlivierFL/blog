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
        $this->render('layout/login.html.twig');
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
}
