<?php

session_start();
use Core\Router;

require '../vendor/autoload.php';

$router = new Router();
$router->run();
