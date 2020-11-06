<?php

namespace App\Controller;

use Core\Controller;
use ReflectionException;

class CommentsController extends Controller
{
    /**
     * @throws ReflectionException
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
