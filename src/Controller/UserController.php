<?php

namespace App\Controller;

use Core\Controller;

class UserController extends Controller
{
    public function login()
    {
        return $this->render('layout/login.html.twig');
    }
}
