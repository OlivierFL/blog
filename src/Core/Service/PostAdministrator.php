<?php

namespace App\Core\Service;

use App\Core\Validation\ValidatorFactory;
use App\Managers\PostManager;
use App\Model\Post;
use Cocur\Slugify\Slugify;
use Exception;

class PostAdministrator
{
    private PostManager $postManager;
    /**
     * @var Slugify
     */
    private Slugify $slugify;

    /**
     * PostAdministrator constructor.
     */
    public function __construct()
    {
        $this->postManager = new PostManager();
        $this->slugify = new Slugify();
    }

    /**
     * @param array  $data
     * @param string $adminId
     *
     * @throws Exception
     *
     * @return array
     */
    public function create(array $data, string $adminId): array
    {
        $slug = $this->createSlug($data['title']);
        $validator = ValidatorFactory::create('post_create', $data);
        if ($validator->isValid()) {
            $post = new Post($data);
            $post->setCreatedAt((new \DateTime())->format('Y-m-d H:i:s'));
            $post->setUpdatedAt((new \DateTime())->format('Y-m-d H:i:s'));
            $post->setAdminId($adminId);
            $post->setSlug($slug);

            $result = $this->postManager->create($post);

            if (false === $result) {
                throw new Exception('Erreur lors de la création de l\'article');
            }

            return ['Nouvel article créé avec succès'];
        }

        return $validator->getErrors();
    }

    /**
     * @param $title
     *
     * @throws Exception
     *
     * @return string
     */
    private function createSlug($title): string
    {
        $slug = $this->slugify->slugify($title);

        $count = 0;
        while (false === $this->checkSlug($slug)) {
            $slug = rtrim($slug, '-0123456789').'-'.++$count;
        }

        return $slug;
    }

    /**
     * @param $slug
     *
     * @throws Exception
     *
     * @return bool
     */
    private function checkSlug($slug): bool
    {
        return $this->postManager->preventReuse(['slug' => $slug]);
    }
}
