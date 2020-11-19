<?php

require '../vendor/autoload.php';
session_start();

use Core\Router;

$router = new Router();
$router->run();
