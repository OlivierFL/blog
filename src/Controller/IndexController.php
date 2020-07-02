<?php

namespace App\Controller;

use Core\Controller;
use Exception;

class IndexController extends Controller
{
    /**
     * @throws Exception
     */
    public function home()
    {
        return $this->render('layout/index.html.twig');
    }
}
