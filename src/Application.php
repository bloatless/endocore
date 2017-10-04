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

    /**
     * @var Environment $environment
     */
    public $environment;

    public function __construct(array $config, RouterInterface $router)
    {
        $this->config = $config;
        $this->router = $router;
        $this->environment = new Environment;
    }

    public function run()
    {
        $this->dispatch();
    }

    protected function dispatch()
    {
        $httpMethod = $this->environment->getRequestMethod();
        $uri = $this->environment->getRequestUri();
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

    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;
    }
}
