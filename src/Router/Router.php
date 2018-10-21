<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Router;

use FastRoute;
use FastRoute\RouteCollector;

class Router implements RouterInterface
{
    /**
     * @var array $routes
     */
    protected $routes;

    /**
     * @var FastRoute\Dispatcher $routeDispatcher
     */
    protected $routeDispatcher;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
        $this->bootstrapDispatcher();
    }

    protected function bootstrapDispatcher()
    {
        $this->routeDispatcher = FastRoute\simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $routeName => $route) {
                $collector->addRoute($route['method'], $route['pattern'], $route['handler']);
            }
        });
    }

    public function dispatch(string $httpMethod, string $uri) : array
    {
        $urlPath = rawurldecode(parse_url($uri, PHP_URL_PATH));
        $routeInfo = $this->routeDispatcher->dispatch($httpMethod, $urlPath);
        return $routeInfo;
    }
}
