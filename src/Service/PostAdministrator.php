<?php

namespace App\Service;

use App\Core\Session;
use App\Core\Validation\Validator;
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
     * @param Post  $post
     * @param array $data
     *
     * @throws FileUploadException
     * @throws PostException
     * @throws ReflectionException
     * @throws Exception
     */
    public function updatePost(Post $post, array $data): void
    {
        $validator = (new Validator($data))->getPostUpdateValidator();

        if ($validator->isValid()) {
            // Delete old cover image if there is a new uploaded image
            if ((isset($_FILES) && 4 !== $_FILES['cover_img']['error']) && null !== $post->getCoverImg()) {
                $this->fileUploader->delete($post->getCoverImg());
            }

            // Update current Post with new data from update form
            $post->hydrate($data);
            $this->createOrUpdatePost($post, true);

            $this->session->addMessages('Article mis à jour');

            return;
        }

        $this->session->addMessages($validator->getErrors());
    }

    /**
     * @param Post $post
     *
     * @throws FileDeleteException
     * @throws PostException
     * @throws Exception
     */
    public function deletePost(Post $post): void
    {
        if ($post->getCoverImg()) {
            $this->fileUploader->delete($post->getCoverImg());
        }

        try {
            $this->postManager->delete($post);
        } catch (Exception $e) {
            throw PostException::delete($post->getId());
        }

        $this->session->addMessages('Article supprimé');
    }

    /**
     * @param Post $post
     * @param bool $update
     *
     * @throws PostException
     * @throws ReflectionException
     * @throws FileUploadException
     * @throws Exception
     */
    private function createOrUpdatePost(Post $post, bool $update = false): void
    {
        if (isset($_FILES) && 4 !== $_FILES['cover_img']['error']) {
            $file = $this->fileUploader->checkFile($_FILES['cover_img'], FileUploader::IMAGE);
            $post->setCoverImg($this->fileUploader->upload($file));
        }
        $post->setUserId($this->session->get('current_user')->getId());

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
