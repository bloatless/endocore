<?php

declare(strict_types=1);

/**
 * Endocore framework
 * by Simon Samtleben <foo@bloatless.org>
 *
 * @link https://bloatless.org
 * @license MIT
 */

namespace Bloatless\Endocore;

use Bloatless\Endocore\Components\Http\Exception\BadRequestException;
use Bloatless\Endocore\Components\Http\Exception\MethodNotAllowedException;
use Bloatless\Endocore\Components\Http\Exception\NotFoundException;
use Bloatless\Endocore\Contracts\ErrorHandler\ErrorHandler as ErrorHandlerContract;
use Bloatless\Endocore\Contracts\Router\Router as RouterContract;
use Bloatless\Endocore\Contracts\Router\Route as RouteContract;
use Bloatless\Endocore\Exception\Application\EndocoreException;
use Bloatless\Endocore\Http\Request;
use Bloatless\Endocore\Http\Response;
use League\Container\Container;
use League\Container\Definition\DefinitionInterface;
use League\Container\ReflectionContainer;

class Application
{
    /* @var array $config */
    public array $config;

    /* @var Request $request */
    public Request $request;

    /* @var RouterContract $router */
    public RouterContract $router;

    /* @var ErrorHandlerContract $errorHandler */
    public ErrorHandlerContract $errorHandler;


    /** @var Container $container */
    public Container $container;

    public function __construct(
        array $config,
        RouterContract $router,
        ErrorHandlerContract $errorHandler
    ) {
        $this->config = $config;
        $this->router = $router;
        $this->errorHandler = $errorHandler;

        $this->container = new Container();
        $this->container->delegate(new ReflectionContainer());

        $this->setErrorHandlers();
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

    protected function setErrorHandlers(): void
    {
        set_error_handler([$this->errorHandler, 'handleError']);
        set_exception_handler([$this->errorHandler, 'handleException']);
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
