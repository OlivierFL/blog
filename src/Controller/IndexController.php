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
        $admin= $this->userAdministrator->getUser(33);
        $posts = $this->postManager->findAllWithAuthor(3);

        $this->render('layout/index.html.twig', [
            'admin' => $admin,
            'posts' => $posts,
        ]);
    }

    /**
     * @throws Exception
     */
    public function notFound(): void
    {
        $this->render('layout/404.html.twig');
    }
}
