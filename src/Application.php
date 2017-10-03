<?php
namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Interfaces\RouterInterface;

class Application
{
    /**
     * @var array $config
     */
    public $config;

    /**
     * @var RouterInterface $router
     */
    public $router;

    public function __construct(array $config, RouterInterface $router)
    {
        $this->config = $config;
        $this->router = $router;
    }

    public function run()
    {
        $this->dispatch();
    }

    protected function dispatch()
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        $routeInfo = $this->router->dispatch($httpMethod, $uri);
        if (!isset($routeInfo[0])) {
            // @todo handle invalid request
        }
        switch ($routeInfo[0]) {
            case Router::NOT_FOUND:
                // @todo handle not found
                break;
            case Router::METHOD_NOT_ALLOWED:
                // @todo handle not allowed
                break;
            case Router::FOUND:
                // @todo handle found
                break;
            default:
                // @todo handle invalid route
        }
    }
}
