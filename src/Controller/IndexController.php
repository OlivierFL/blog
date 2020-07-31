<?php

namespace App\Controller;

use Core\Controller;
use Exception;

class IndexController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(): void
    {
        $this->render('layout/index.html.twig');
    }

    /**
     * @throws Exception
     */
    public function notFound(): void
    {
        $this->render('layout/404.html.twig');
    }
}
