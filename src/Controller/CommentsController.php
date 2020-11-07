<?php

namespace App\Controller;

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

    public function __construct()
    {
        parent::__construct();
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
