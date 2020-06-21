<?php

namespace Core\Router;

use App\Controller\IndexController;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router
{
    private $dispatcher;

    public function __construct()
    {
        $this->dispatcher = simpleDispatcher(static function (RouteCollector $routes) {
            $routes->get('/', 'index');
            $routes->addGroup('/posts', static function (RouteCollector $routes) {
                $routes->get('', 'posts.list');
                $routes->get('/', 'posts.list');
                $routes->get('/{id:\d+}', 'posts.show');
            });
        });
    }

    public function run()
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $matchedRoute = $this->dispatcher->dispatch($httpMethod, $uri);

        $this->handleRoute($matchedRoute);
    }

    private function handleRoute(array $matchedRoute)
    {
        $routeName = $matchedRoute[1];
        $params = $matchedRoute[2];

        switch ($matchedRoute[0]) {
            case Dispatcher::NOT_FOUND:
                echo '404 not found';

                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                echo 'Method not allowed';

                break;
            case Dispatcher::FOUND:
                if ('index' === $routeName) {
                    $controller = new IndexController();

                    return $controller->home();
                }

                if (preg_match('/(\w+)\.?(\w+)?/', $routeName, $matches)) {
                    $controller = $this->formatControllerName($matches[1]);
                    $controller = new $controller();
                    if ($matches[2]) {
                        $action = $matches[2];
                    }
                    if (!empty($params) && $params['id']) {
                        return $controller->{$action}($params['id']);
                    }

                    return $controller->{$action}();
                }

                break;
            default:
                echo '404 not found';
        }
    }

    private function formatControllerName(string $name): string
    {
        $controller = ucfirst($name).'Controller';
        $namespace = 'App\\Controller\\';

        return $namespace.$controller;
    }
}
