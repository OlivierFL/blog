<?php

namespace App\Service;

use App\Core\Session;
use App\Core\Validation\Validator;
use App\Exceptions\DatabaseException;
use App\Exceptions\FileDeleteException;
use App\Exceptions\FileUploadException;
use App\Exceptions\PostException;
use App\Managers\PostManager;
use App\Model\Post;
use Exception;
use ReflectionException;

class PostAdministrator
{
    /**
     * @var PostManager
     */
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
     * @throws DatabaseException
     * @throws FileUploadException
     * @throws PostException
     * @throws ReflectionException
     * @throws Exception
     */
    public function createPost(array $data): void
    {
        $validator = (new Validator($data))->getBaseValidator();
        if ($validator->isValid()) {
            $this->createOrUpdatePost(new Post($data));

            $this->session->addMessages('Nouvel article créé avec succès');

            return;
        }

        $this->session->addMessages($validator->getErrors());
    }

    /**
     * @param array $post
     * @param array $data
     *
     * @throws DatabaseException
     * @throws FileUploadException
     * @throws PostException
     * @throws ReflectionException
     * @throws Exception
     */
    public function updatePost(array $post, array $data): void
    {
        $validator = (new Validator($data))->getPostUpdateValidator();

        if ($validator->isValid()) {
            $updatedPost = new Post($post);
            // Update current Post with new data from update form
            $updatedPost->hydrate($data);
            $this->createOrUpdatePost($updatedPost, true);

            $this->session->addMessages('Article mis à jour');

            return;
        }

        $this->session->addMessages($validator->getErrors());
    }

    /**
     * @param array $post
     *
     * @throws FileDeleteException
     * @throws PostException
     * @throws Exception
     */
    public function deletePost(array $post): void
    {
        $deletedPost = new Post($post);

        if ($deletedPost->getCoverImg()) {
            $this->fileUploader->delete($deletedPost->getCoverImg());
        }

        try {
            $this->postManager->delete($deletedPost);
        } catch (Exception $e) {
            throw PostException::delete($deletedPost->getId());
        }

        $this->session->addMessages('Article supprimé');
    }

    /**
     * @param Post $post
     * @param bool $update
     *
     * @throws PostException
     * @throws ReflectionException
     * @throws DatabaseException
     * @throws FileUploadException
     * @throws Exception
     */
    private function createOrUpdatePost(Post $post, bool $update = false): void
    {
        if (isset($_FILES) && 4 !== $_FILES['cover_img']['error']) {
            $file = $this->fileUploader->checkFile($_FILES['cover_img'], FileUploader::IMAGE);
            $post->setCoverImg($this->fileUploader->upload($file));
        }
        $post->setUserId($this->session->get('current_user')['id']);

        if ($update) {
            $result = $this->postManager->update($post);
        } else {
            $result = $this->postManager->create($post);
        }

        if (false === $result) {
            // If Post creation fails and if an image was uploaded, delete the uploaded post cover image
            null === $post->getCoverImg() ?: $this->fileUploader->delete($post->getCoverImg());

            if (true === $update) {
                throw PostException::update($post->getId());
            }

            throw PostException::create();
        }
    }
}
