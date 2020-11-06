<?php

namespace App\Service;

use App\Managers\PostManager;

class Paginator
{
    /**
     * @var PostManager
     */
    private PostManager $postManager;

    /**
     * Paginator constructor.
     */
    public function __construct()
    {
        $this->postManager = new PostManager();
    }

    /**
     * @param null|int $page
     * @param null|int $perPage
     *
     * @return array
     */
    public function getPostsPaginated(int $page, ?int $perPage = 3): array
    {
        $offset = $page > 1 ? ($page - 1) * $perPage : 0;

        $posts = $this->postManager->findAllWithAuthorPaginated($perPage, $offset);

        $posts['max_pages'] = (int) ceil($posts['total'] / $perPage);
        $posts['previous_page'] = $page > 0 ? $page - 1 : 1;
        $posts['next_page'] = $page > $posts['max_pages'] ? $posts['max_pages'] : $page + 1;

        return $posts;
    }
}
