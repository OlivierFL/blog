<?php

namespace App\Service;

use App\Core\Session;
use App\Core\Validation\Validator;
use App\Exceptions\CommentException;
use App\Exceptions\DatabaseException;
use App\Managers\CommentManager;
use App\Model\Comment;
use Exception;
use ReflectionException;

class CommentAdministrator
{
    /**
     * @var CommentManager
     */
    private CommentManager $commentManager;
    /** œ.
     * @var Session
     */
    private Session $session;

    /**
     * CommentAdministrator constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->commentManager = new CommentManager();
        $this->session = $session;
    }

    /**
     * @param array $data
     *
     * @throws CommentException
     * @throws DatabaseException
     * @throws ReflectionException
     */
    public function createComment(array $data): void
    {
        $validator = (new Validator($data))->getBaseValidator();

        if ($validator->isValid()) {
            $this->create($data);

            $this->session->addMessages('Votre commentaire a été soumis pour validation, il sera visible dès sa validation par l\'administrateur du site');

            return;
        }

        $this->session->addMessages($validator->getErrors());
    }

    /**
     * @param Comment $comment
     * @param array   $data
     *
     * @throws Exception
     */
    public function updateComment(Comment $comment, array $data): void
    {
        switch ($data) {
            case 'reject' === array_key_first($data):
                $comment->setStatus(Comment::STATUS_REJECTED);
                $message = 'Commentaire non validé';

                break;
            case 'approve' === array_key_first($data):
                $comment->setStatus(Comment::STATUS_APPROVED);
                $message = 'Commentaire approuvé';

                break;
            default:
                throw CommentException::invalidStatus($comment->getId());
        }

        try {
            $this->commentManager->update($comment);
        } catch (DatabaseException | ReflectionException $e) {
            throw CommentException::update($comment->getId());
        }

        if (Comment::STATUS_APPROVED === $comment->getStatus()) {
            $this->session->addMessages($message);

            return;
        }

        $this->session->addMessages($message);
    }

    /**
     * @param array $data
     *
     * @throws CommentException
     * @throws ReflectionException
     * @throws DatabaseException
     */
    private function create(array $data): void
    {
        $comment = new Comment($data);
        $comment->setUserId($this->session->get('current_user')['id']);

        $result = $this->commentManager->create($comment);

        if (false === $result) {
            throw CommentException::create();
        }
    }
}
