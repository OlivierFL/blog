<?php

namespace App\Service;

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
     * @var FileUploader
     */
    private FileUploader $fileUploader;

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
        $this->fileUploader = new FileUploader();
    }

    /**
     * @param array $data
     *
     * @throws Exception
     *
     * @return array
     */
    public function createPost(array $data): array
    {
        $validator = ValidatorFactory::create('create_post', $data);
        if ($validator->isValid()) {
            try {
                $this->createOrUpdatePost($data);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
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
            try {
                $this->createOrUpdatePost($post, true);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

            return ['Article mis à jour'];
        }

        return $validator->getErrors();
    }

    /**
     * @param array $post
     *
     * @throws Exception
     *
     * @return bool
     */
    public function deletePost(array $post): bool
    {
        $deletedPost = new Post($post);

        try {
            return $this->postManager->delete($deletedPost);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la suppression de l\'article');
        }
    }

    /**
     * @param array $data
     * @param bool  $update
     *
     * @throws ReflectionException
     * @throws Exception
     */
    private function createOrUpdatePost(array $data, bool $update = false): void
    {
        if (isset($_FILES) && 4 !== $_FILES['cover_img']['error']) {
            try {
                $file = $this->fileUploader->checkFile($_FILES['cover_img'], FileUploader::IMAGE);
                $data['cover_img'] = $this->fileUploader->upload($file);
            } catch (Exception $e) {
                throw new Exception('Erreur lors du téléchargement de l\'image : '.$e->getMessage());
            }
        }
        $post = new Post($data);
        $post->setCreatedAt($post->getCreatedAt() ?? (new \DateTime())->format('Y-m-d H:i:s'));
        $post->setUpdatedAt((new \DateTime())->format('Y-m-d H:i:s'));
        $post->setAdminId($this->session->get('current_user')['admin_infos']['id']);

        if ($update) {
            $result = $this->postManager->update($post);
        } else {
            $result = $this->postManager->create($post);
        }

        if (false === $result) {
            // If Post creation fails and if an image was uploaded, delete the uploaded post cover image
            null === $post->getCoverImg() ?: $this->fileUploader->delete($post->getCoverImg());

            throw new Exception('Erreur lors de la création de l\'article');
        }
    }
}
