<?php
namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Exceptions\Http\MethodNotAllowedException;
use Nekudo\ShinyCore\Exceptions\Http\NotFoundException;
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
     * @var Request $request
     */
    public $request;

    public function __construct(array $config, Request $request, RouterInterface $router)
    {
        $this->config = $config;
        $this->router = $router;
        $this->request = $request;
    }

    public function run()
    {
        try {
            $this->dispatch();
        } catch (\Error $e) {
            (new ExceptionHandler)->handleError($e);
        } catch (\Exception $e) {
            (new ExceptionHandler)->handleException($e);
        }
    }

    protected function dispatch()
    {
        $httpMethod = $this->request->getRequestMethod();
        $uri = $this->request->getRequestUri();
        $routeInfo = $this->router->dispatch($httpMethod, $uri);
        if (!isset($routeInfo[0])) {
            // @todo handle invalid request
        }
        switch ($routeInfo[0]) {
            case Router::NOT_FOUND:
                throw new NotFoundException('Page not found.');
            case Router::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException('Method not allowed');
            case Router::FOUND:
                $action = $routeInfo[1]['action'];
                $domain = $routeInfo[1]['domain'] ?? '';
                $arguments = $routeInfo[2];
                $this->callAction($action, $domain, $arguments);
                break;
            default:
                // @todo handle invalid route
        }
    }

    public function callAction(string $handler, string $domainName, array $arguments = [])
    {
        if (!class_exists($handler)) {
            // @todo Handle class not found error
        }

        /** @var \Nekudo\ShinyCore\Interfaces\ActionInterface $action */
        $action = new $handler($this->request);
        if (!empty($domainName) && class_exists($domainName)) {
            $domain = new $domainName;
            $action->setDomain($domain);
        }
        $action->__invoke($arguments);
    }
}
