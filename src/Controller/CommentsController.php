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
        $result = $this->commentAdministrator->createComment($_POST);

        $this->addMessage($result);

        if (preg_match('/[a-z0-9-]+/', $slug)) {
            header('Location: /posts/'.$slug);
            die();
        }

        $slug = null;
        header('Location: /');
        die();
    }
}
