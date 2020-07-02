<?php

namespace App\Controller;

use Core\Controller;

class AdminController extends Controller
{
    public function home()
    {
        return $this->render('admin/index.html.twig');
    }
}
