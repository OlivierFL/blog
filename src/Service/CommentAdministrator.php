<?php

namespace App\Service;

use App\Core\Session;
use App\Core\Validation\Validator;
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
     * @throws ReflectionException
     */
    public function createComment(array $data): void
    {
        $validator = (new Validator($data))->getCommentValidator();

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
        if ($data['reject']) {
            $comment->setStatus(Comment::STATUS_REJECTED);
        } elseif ($data['approve']) {
            $comment->setStatus(Comment::STATUS_APPROVED);
        } else {
            throw new Exception('Changement de statut invalide');
        }

        try {
            $this->commentManager->update($comment);
        } catch (ReflectionException $e) {
            throw new Exception('Erreur lors la mise à jour du commentaire : '.$comment->getId());
        }

        if (Comment::STATUS_APPROVED === $comment->getStatus()) {
            $this->session->addMessages('Commentaire approuvé');

            return;
        }

        $this->session->addMessages('Commentaire non validé');
    }

    /**
     * @param array $data
     *
     * @throws ReflectionException
     * @throws Exception
     */
    private function create(array $data): void
    {
        $comment = new Comment($data);
        $comment->setUserId($this->session->get('current_user')['base_infos']['id']);

        $result = $this->commentManager->create($comment);

        if (false === $result) {
            throw new Exception('Erreur lors de la création du commentaire');
        }
    }
}
