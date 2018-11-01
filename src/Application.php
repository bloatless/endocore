<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Exceptions\Application\ShinyCoreException;
use Nekudo\ShinyCore\Exceptions\ExceptionHandlerInterface;
use Nekudo\ShinyCore\Exceptions\Http\BadRequestException;
use Nekudo\ShinyCore\Exceptions\Http\MethodNotAllowedException;
use Nekudo\ShinyCore\Exceptions\Http\NotFoundException;
use Nekudo\ShinyCore\Logger\LoggerInterface;
use Nekudo\ShinyCore\Router\RouterInterface;
use Nekudo\ShinyCore\Router\Router;

class Application
{
    /**
     * @var Config $config
     */
    public $config;

    /**
     * @var Request $request
     */
    public $request;

    /**
     * @var RouterInterface $router
     */
    public $router;

    /**
     * @var LoggerInterface $logger
     */
    public $logger;

    /**
     * @var ExceptionHandlerInterface $exceptionHandler
     */
    public $exceptionHandler;

    public function __construct(
        Config $config,
        Request $request,
        RouterInterface $router,
        LoggerInterface $logger,
        ExceptionHandlerInterface $exceptionHandler
    ) {
        $this->config = $config;
        $this->router = $router;
        $this->request = $request;
        $this->logger = $logger;
        $this->exceptionHandler = $exceptionHandler;
    }

    public function run()
    {
        try {
            $this->dispatch();
        } catch (\Error $e) {
            $this->exceptionHandler->handleError($e);
        } catch (\Exception $e) {
            $this->exceptionHandler->handleException($e);
        }
    }

    protected function dispatch()
    {
        $httpMethod = $this->request->getRequestMethod();
        $uri = $this->request->getRequestUri();
        $routeInfo = $this->router->dispatch($httpMethod, $uri);
        if (!isset($routeInfo[0])) {
            throw new BadRequestException('Unable to parse request.');
        }
        switch ($routeInfo[0]) {
            case Router::NOT_FOUND:
                throw new NotFoundException('Page not found.');
            case Router::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException('Method not allowed');
            case Router::FOUND:
                $action = $routeInfo[1];
                $arguments = $routeInfo[2];
                $this->callAction($action, $arguments);
                break;
            default:
                throw new BadRequestException('Unable to parse request.');
        }
    }

    public function callAction(string $handler, array $arguments = [])
    {
        if (!class_exists($handler)) {
            throw new ShinyCoreException('Action class not found.');
        }

        /** @var \Nekudo\ShinyCore\Actions\ActionInterface $action */
        $action = new $handler($this->config, $this->request);
        $action->__invoke($arguments);
        $action->getResponder()->respond();
    }
}
