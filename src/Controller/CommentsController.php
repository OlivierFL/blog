<?php

namespace App\Controller;

use App\Exceptions\AccessDeniedException;
use App\Exceptions\CommentException;
use App\Exceptions\DatabaseException;
use App\Service\CommentAdministrator;
use Core\Controller;
use ReflectionException;

class CommentsController extends Controller
{
    /**
     * @var CommentAdministrator
     */
    private CommentAdministrator $commentAdministrator;
    /**
     * @var array|string[]
     */
    private array $roles = [
        'admin',
        'user',
    ];

    /**
     * CommentsController constructor.
     *
     * @throws AccessDeniedException
     */
    public function __construct()
    {
        parent::__construct();
        if (!\in_array($this->auth->getCurrentUserRole(), $this->roles, true)) {
            throw new AccessDeniedException('Accès non autorisé !');
        }
        $this->commentAdministrator = new CommentAdministrator($this->session);
    }

    /**
     * @throws ReflectionException
     * @throws CommentException
     * @throws DatabaseException
     */
    public function create(): void
    {
        $slug = $_POST['post_slug'];
        $this->commentAdministrator->createComment($_POST);

        if (preg_match('/[a-z0-9-]+/', $slug)) {
            header('Location: /posts/'.$slug);
            die();
        }

        header('Location: /');
        die();
    }
}
