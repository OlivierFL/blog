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
        $url = $_SERVER['HTTP_REFERER'];
        $result = $this->commentAdministrator->createComment($_POST);

        $this->addMessage($result);

        header('Location: '.$url);
        die();
    }
}
