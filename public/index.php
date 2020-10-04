<?php

use Core\Router;

require '../vendor/autoload.php';

session_start();
$router = new Router();
$router->run();
