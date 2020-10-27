<?php

namespace App\Controller;

use App\Exceptions\TwigException;
use Core\Controller;
use Exception;

class IndexController extends Controller
{
    /**
     * @throws TwigException
     * @throws Exception
     */
    public function index(): void
    {
        $description = $this->adminManager->findOneBy(['id' => 5]);
        $posts = $this->postManager->findAllWithAuthor(3);

        $this->render('layout/index.html.twig', [
            'description' => $description['description'],
            'posts' => $posts,
        ]);
    }

    /**
     * @throws TwigException
     */
    public function notFound(): void
    {
        $this->render('layout/errors/404.html.twig');
    }
}
