<?php

namespace Core\Router;

use App\Controller\IndexController;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router
{
    public function run()
    {
        $dispatcher = simpleDispatcher(static function (RouteCollector $routes) {
            $routes->get('/', 'index');
            $routes->addGroup('/posts', static function (RouteCollector $routes) {
                $routes->get('', 'posts.list');
                $routes->get('/', 'posts.list');
                $routes->get('/{id:\d+}', 'posts.show');
            });
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                echo '404 not found';

                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                echo 'Method not allowed';

                break;
            case Dispatcher::FOUND:
                if ('index' === $routeInfo[1]) {
                    $controller = new IndexController();
                    $controller->home();
                } elseif (preg_match('/(\w+)\.?(\w+)?/', $routeInfo[1], $matches)) {
                    $controller = $this->getControllerName($matches[1]);
                    $controller = new $controller();
                    if ($matches[2]) {
                        $action = $matches[2];
                    }
                    if (!empty($routeInfo[2]) && $routeInfo[2]['id']) {
                        $id = $routeInfo[2]['id'];
                        $controller->{$action}($id);
                    } else {
                        $controller->{$action}();
                    }
                }

                break;
            default:
                echo '404 not found';
        }
    }

    private function getControllerName(string $name): string
    {
        $controller = ucfirst($name) . 'Controller';
        $namespace = 'App\\Controller\\';

        return $namespace . $controller;
    }
}
