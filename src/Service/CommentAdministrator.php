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
     *
     * @return array|string
     */
    public function createComment(array $data)
    {
        $validator = (new Validator($data))->getCommentValidator();

        if ($validator->isValid()) {
            $this->createOrUpdateComment($data);

            return 'Votre commentaire a été soumis pour validation, il sera visible dès sa validation par l\'administrateur du site';
        }

        return $validator->getErrors();
    }

    /**
     * @param array $data
     * @param bool  $update
     *
     * @throws ReflectionException
     * @throws Exception
     */
    private function createOrUpdateComment(array $data, bool $update = false): void
    {
        $comment = new Comment($data);
        $comment->setUserId($this->session->get('current_user')['base_infos']['id']);

        if ($update) {
            $result = $this->commentManager->update($comment);
        } else {
            $result = $this->commentManager->create($comment);
        }

        if (false === $result) {
            throw new Exception('Erreur lors de la création du commentaire');
        }
    }
}
