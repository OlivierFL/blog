<?php

namespace App\Service;

use App\Core\Session;
use App\Core\Validation\Validator;
use App\Managers\PostManager;
use App\Model\Post;
use Exception;
use ReflectionException;

class PostAdministrator
{
    private PostManager $postManager;
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
        $this->fileUploader = new FileUploader();
    }

    /**
     * @param array $data
     *
     * @throws Exception
     */
    public function createPost(array $data): void
    {
        $validator = (new Validator($data))->getPostCreateValidator();
        if ($validator->isValid()) {
            try {
                $this->createOrUpdatePost($data);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

            $this->session->addMessages('Nouvel article créé avec succès');

            return;
        }

        $this->session->addMessages($validator->getErrors());
    }

    /**
     * @param array $post
     * @param array $data
     *
     * @throws Exception
     */
    public function updatePost(array $post, array $data): void
    {
        $post = $this->updatePostWithNewValues($post, $data);
        $validator = (new Validator($data))->getPostUpdateValidator();

        if ($validator->isValid()) {
            try {
                $this->createOrUpdatePost($post, true);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

            $this->session->addMessages('Article mis à jour');

            return;
        }

        $this->session->addMessages($validator->getErrors());
    }

    /**
     * @param array $post
     *
     * @throws Exception
     */
    public function deletePost(array $post): void
    {
        $deletedPost = new Post($post);

        try {
            if ($deletedPost->getCoverImg()) {
                $this->fileUploader->delete($deletedPost->getCoverImg());
            }

            $this->postManager->delete($deletedPost);

            $this->session->addMessages('Article supprimé');

            return;
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

    /**
     * @param array $post
     * @param array $data
     *
     * @return array
     */
    private function updatePostWithNewValues(array $post, array $data): array
    {
        foreach ($post as $key => $value) {
            if (isset($data[$key]) && $value !== $data[$key]) {
                $post[$key] = $data[$key];
            }
        }

        return $post;
    }
}
