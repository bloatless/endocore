<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Core\Router;

use Bloatless\Endocore\Contracts\Router\RouterContract;
use FastRoute;
use FastRoute\RouteCollector;

class Router implements RouterContract
{
    /**
     * @var array $routes
     */
    protected array $routes;

    /**
     * @var FastRoute\Dispatcher $routeDispatcher
     */
    protected FastRoute\Dispatcher $routeDispatcher;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
        $this->bootstrapDispatcher();
    }

    /**
     * Prepares the route dispatcher.
     *
     * @return void
     */
    protected function bootstrapDispatcher(): void
    {
        $this->routeDispatcher = FastRoute\simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $routeName => $route) {
                $collector->addRoute($route['method'], $route['pattern'], $route['handler']);
            }
        });
    }

    /**
     * Dispatches HTTP request and returns route information.
     *
     * @param string $httpMethod
     * @param string $uri
     * @return Route
     */
    public function dispatch(string $httpMethod, string $uri) : Route
    {
        $urlPath = rawurldecode(parse_url($uri, PHP_URL_PATH));
        $routeInfo = $this->routeDispatcher->dispatch($httpMethod, $urlPath);
        return new Route(
            $routeInfo[0],
            $routeInfo[1] ?? '',
            $routeInfo[2] ?? []
        );
    }
}
