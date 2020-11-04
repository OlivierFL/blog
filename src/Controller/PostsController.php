<?php

namespace App\Controller;

use App\Service\Paginator;
use Core\Controller;
use Exception;

class PostsController extends Controller
{
    /**
     * @var Paginator
     */
    private Paginator $paginator;

    /**
     * PostsController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->paginator = new Paginator();
    }

    /**
     * @param null|int $page
     *
     * @throws Exception
     */
    public function list(?int $page = 1): void
    {
        $posts = $this->paginator->getPostsPaginated($page);

        $this->render('layout/posts.html.twig', [
            'posts' => $posts,
            'previous' => $posts['previous_page'],
            'next' => $posts['next_page'],
            'page' => $page,
            'max_pages' => $posts['max_pages'],
        ]);
    }

    /**
     * @param string $slug
     *
     * @throws Exception
     */
    public function show(string $slug): void
    {
        $post = $this->postManager->findOneWithAuthorBySlug($slug);

        $this->render('layout/post.html.twig', [
            'post' => $post,
        ]);
    }
}
