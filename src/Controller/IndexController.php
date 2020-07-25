<?php

namespace App\Controller;

use Core\Controller;
use Exception;

class IndexController extends Controller
{
    /**
     * @throws Exception
     */
    public function index()
    {
        return $this->render('layout/index.html.twig');
    }

    public function notFound()
    {
        return $this->render('layout/404.html.twig');
    }
}
