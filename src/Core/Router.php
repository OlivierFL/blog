<?php

namespace Core;

use App\Controller\IndexController;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router
{
    private $dispatcher;

    public function __construct()
    {
        $this->dispatcher = $this->addRoutes();
    }

    private function addRoutes(): Dispatcher
    {
        $routesConfig = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routes.yaml');

        $parsedRoutes = yaml_parse($routesConfig);

        return simpleDispatcher(static function (RouteCollector $routes) use ($parsedRoutes) {
            foreach ($parsedRoutes as $routeGroup => $parsedRoute) {
                $routes->addGroup($routeGroup === 'index' ? '' : '/' . $routeGroup, static function (RouteCollector $routes) use ($parsedRoute, $routeGroup) {
                    foreach ($parsedRoute as $route) {
                        $routes->addRoute($route['methods'], $route['path'], $routeGroup . '.' . $route['action']);
                    }
                });
            }
        });
    }

    /**
     * @param array $matchedRoute
     * $matchedRoute is an array which contains route information returned by the dispatcher
     * $matchedRoute = [
     *      0 => an integer indicating if the route has a match, or the route is not found or the method is not allowed,
     *      1 => handler name specified when adding routes,
     *      2 => an array of "get/post" params if they exists,
     * ]
     * @return mixed
     */
    private function handleRoute(array $matchedRoute)
    {
        $routeName = $matchedRoute[1];
        $params = $matchedRoute[2];

        switch ($matchedRoute[0]) {
            case Dispatcher::NOT_FOUND:
                $controller = new IndexController();
                return $controller->notFound();

                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                echo 'Method not allowed';

                break;
            case Dispatcher::FOUND:
                /**
                 * array $matches contains the results of the preg_match function
                 * $matches[0] contains the full match
                 * $matches[1] contains the match of the first capturing group of the regex (used to get the right controller name)
                 * $matches[2] contains the match of the second capturing group (used to call the right controller action)
                 */
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
        $controller = ucfirst($name) . 'Controller';
        $namespace = 'App\\Controller\\';

        return $namespace . $controller;
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
}
