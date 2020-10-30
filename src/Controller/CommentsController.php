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

        header('Location: /posts/'.$slug);
    }
}
