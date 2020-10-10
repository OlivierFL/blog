<?php

namespace App\Core\Service;

use App\Core\Session;
use App\Core\Validation\ValidatorFactory;
use App\Managers\PostManager;
use App\Model\Post;
use Cocur\Slugify\Slugify;
use Exception;
use ReflectionException;

class PostAdministrator
{
    private PostManager $postManager;
    /**
     * @var Slugify
     */
    private Slugify $slugify;
    /**
     * @var Session
     */
    private Session $session;

    /**
     * PostAdministrator constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        $this->postManager = new PostManager();
        $this->slugify = new Slugify();
    }

    /**
     * @param int $id
     *
     * @throws Exception
     *
     * @return array
     */
    public function getPostWithAuthor(int $id): array
    {
        return $this->postManager->findOneWithAuthor(['id' => $id]);
    }

    /**
     * @param array $data
     *
     * @throws Exception
     * @throws ReflectionException
     *
     * @return array
     */
    public function createPost(array $data): array
    {
        $validator = ValidatorFactory::create('create_post', $data);
        if ($validator->isValid()) {
            $post = new Post($data);
            $post->setCreatedAt((new \DateTime())->format('Y-m-d H:i:s'));
            $post->setUpdatedAt((new \DateTime())->format('Y-m-d H:i:s'));
            $post->setAdminId($this->session->get('current_user')['admin_infos']['id']);
            $post->setSlug($this->createSlug($data['title']));

            $result = $this->postManager->create($post);

            if (false === $result) {
                throw new Exception('Erreur lors de la création de l\'article');
            }

            return ['Nouvel article créé avec succès'];
        }

        return $validator->getErrors();
    }

    /**
     * @param array $post
     * @param array $data
     *
     * @throws Exception
     *
     * @return array
     */
    public function updatePost(array $post, array $data): array
    {
        $post = array_merge($post, $data);
        $validator = ValidatorFactory::create('update_post', $post);

        if ($validator->isValid()) {
            $updatedPost = new Post($post);
            $updatedPost->setAdminId($this->session->get('current_user')['admin_infos']['id']);
            $updatedPost->setUpdatedAt((new \DateTime())->format('Y-m-d H:i:s'));
            $updatedPost->setSlug($this->createSlug($updatedPost->getTitle(), false));

            $result = $this->postManager->update($updatedPost);

            if (false === $result) {
                throw new Exception('Erreur lors de la mise à jour de l\'article');
            }

            return ['Article mis à jour'];
        }

        return $validator->getErrors();
    }

    /**
     * @param      $title
     * @param bool $checkSlug
     *
     * @throws Exception
     *
     * @return string
     */
    private function createSlug($title, bool $checkSlug = true): string
    {
        $slug = $this->slugify->slugify($title);

        if (true === $checkSlug) {
            $count = 0;
            while (false === $this->checkSlug($slug)) {
                $slug = rtrim($slug, '-0123456789').'-'.++$count;
            }
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
