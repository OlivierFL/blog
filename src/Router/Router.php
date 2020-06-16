<?php

namespace App\Router;

use App\Controller\FrontController;

class Router
{
    public function run()
    {
        $frontController = new FrontController();
        $frontController->home();
    }
}
