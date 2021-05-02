<?php

declare(strict_types=1);

namespace Bloatless\Endocore;

use Bloatless\Endocore\Core\ErrorHandler\ErrorHandler;
use Bloatless\Endocore\Core\Http\Exception\BadRequestException;
use Bloatless\Endocore\Core\Http\Exception\MethodNotAllowedException;
use Bloatless\Endocore\Core\Http\Exception\NotFoundException;
use Bloatless\Endocore\Core\Http\Request;
use Bloatless\Endocore\Core\Http\Response;
use Bloatless\Endocore\Core\Router\Router;
use Bloatless\Endocore\Core\Logger\LoggerFactory;
use Bloatless\Endocore\Contracts\ErrorHandler\ErrorHandlerContract;
use Bloatless\Endocore\Contracts\Router\RouterContract;
use Bloatless\Endocore\Contracts\Router\RouteContract;
use Bloatless\Endocore\Exception\Application\EndocoreException;
use League\Container\Container;
use League\Container\Definition\DefinitionInterface;
use League\Container\ReflectionContainer;

class Application
{
    /** @var string $basePath */
    protected string $basePath;

    /* @var array $config */
    public array $config;

    /* @var Request $request */
    public Request $request;

    /* @var RouterContract $router */
    public RouterContract $router;

    /* @var ErrorHandler $errorHandler */
    public ErrorHandler $errorHandler;

    /** @var Container $container */
    public Container $container;

    public function __construct(string $basePath)
    {
        $this->setBasePath($basePath);
        $this->initConfig();
        $this->initContainer();
        $this->initErrorHandler();
        $this->initRouter();
    }

    public function setBasePath(string $basePath): void
    {
        $ds = DIRECTORY_SEPARATOR;
        $this->basePath = rtrim($basePath, $ds) . $ds;
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function setErrorHandler(ErrorHandlerContract $errorHandler): void
    {
        $this->errorHandler = $errorHandler;
        set_error_handler([$this->errorHandler, 'handleError']);
        set_exception_handler([$this->errorHandler, 'handleException']);
    }

    public function setRouter(RouterContract $router): void
    {
        $this->router = $router;
    }

    protected function initConfig(): void
    {
        $config = require $this->basePath() . 'config/config.php';
        $this->setConfig($config);
    }

    protected function initContainer(): void
    {
        $this->container = new Container();
        $this->container->delegate(new ReflectionContainer());
    }

    protected function initErrorHandler(): void
    {
        $loggerFactory = new LoggerFactory($this->config);
        $errorHandler = new ErrorHandler(
            $loggerFactory->make()
        );
        $this->setErrorHandler($errorHandler);
    }

    protected function initRouter(): void
    {
        $pathRoutes = $this->basePath() . 'routes/default.php';
        if (!file_exists($pathRoutes)) {
            throw new EndocoreException(sprintf('Routes file not found at %s', $pathRoutes));
        }
        $routes = require_once $pathRoutes;
        $router = new Router($routes);
        $this->setRouter($router);
    }

    protected function basePath(): string
    {
        return $this->basePath;
    }

    /**
     * Registers a component.
     *
     * @param string $id
     * @param string $factory
     */
    public function addComponent(string $id, string $factory): void
    {
        $this->register($id, function () use ($factory) {
            $componentFactory = new $factory($this->config);
            return $componentFactory->make();
        });
    }

    /**
     * Adds/Registers a new item to the container.
     *
     * @param string $id
     * @param null $concrete
     * @param bool $shared
     * @return DefinitionInterface
     */
    public function register(string $id, $concrete = null, bool $shared = false): DefinitionInterface
    {
        return $this->container->add($id, $concrete, $shared);
    }

    /**
     * Runs the application and passes all errors to exception handler.
     *
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $this->errorHandler->setRequest($request);
        $this->container->add(Request::class, $request);
        $response = $this->dispatch($request);
        $this->send($response);

        return $response;
    }

    /**
     * Analyzes the request using the router and calls corresponding action.
     * Throws HTTP exceptions in case request could not be assigned to an action.

     * @param Request $request
     * @throws BadRequestException
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @throws EndocoreException
     * @return Response
     */
    protected function dispatch(Request $request): Response
    {
        $route = $this->router->dispatch(
            $request->getRequestMethod(),
            $request->getRequestUri()
        );

        switch ($route->getState()) {
            case RouteContract::NOT_FOUND:
                throw new NotFoundException('Page not found.');
            case RouteContract::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException('Method not allowed');
            case RouteContract::FOUND:
                return $this->callAction(
                    $request,
                    $route->getHandler(),
                    $route->getArguments()
                );
            default:
                throw new BadRequestException('Unable to parse request.');
        }
    }

    /**
     * Calls an action.
     *
     * @param Request $request
     * @param string $handler
     * @param array $arguments
     * @throws EndocoreException
     * @return Response
     */
    protected function callAction(Request $request, string $handler, array $arguments = []): Response
    {
        if (!class_exists($handler)) {
            throw new EndocoreException('Action class not found.');
        }

        /** @var \Bloatless\Endocore\Contracts\Action\Action $action */
        $action = $this->container->get($handler);

        return $action->__invoke($request, $arguments);
    }

    /**
     * Sends response to client.
     *
     * @param Response $response
     * @return void
     */
    public function send(Response $response): void
    {
        // send http header:
        $httpHeader = sprintf(
            'HTTP/%s %d %s',
            $response->getProtocolVersion(),
            $response->getStatus(),
            $response->getStatusMessage()
        );
        header($httpHeader, true);

        // send additional headers:
        foreach ($response->getHeaders() as $name => $value) {
            header(sprintf('%s: %s', $name, $value), true);
        }

        // send body:
        echo $response->getBody();
    }
}
