<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Exceptions;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Exceptions\Http\BadRequestException;
use Nekudo\ShinyCore\Exceptions\Http\MethodNotAllowedException;
use Nekudo\ShinyCore\Exceptions\Http\NotFoundException;
use Nekudo\ShinyCore\Logger\LoggerInterface;
use Nekudo\ShinyCore\Request;
use Nekudo\ShinyCore\Responder\HtmlResponder;
use Nekudo\ShinyCore\Responder\JsonResponder;
use Nekudo\ShinyCore\Responder\ResponderInterface;

class ExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    /**
     * @var Request $request
     */
    protected $request;

    public function __construct(Config $config, LoggerInterface $logger, Request $request)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->request = $request;
    }

    /**
     * Handles internal php errors.
     *
     * @param \Error $e
     */
    public function handleError(\Error $e): void
    {
        $this->logger->error(sprintf('%s in %s:%d', $e->getMessage(), $e->getFile(), $e->getLine()));
        $this->respondPhpError($e);
    }

    /**
     * Handles exceptions thrown by application.
     *
     * @param \Exception $e
     */
    public function handleException(\Exception $e): void
    {
        if ($e instanceof NotFoundException) {
            $this->logger->info('404 Not Found: ' . $this->request->getRequestUri());
            $this->respondNotFound();
        } elseif ($e instanceof MethodNotAllowedException) {
            $this->logger->notice('Method not allowed.', [
                'request_uri' => $this->request->getRequestUri(),
                'request_method' => $this->request->getRequestMethod(),
            ]);
            $this->respondMethodNotAllowed();
        } elseif ($e instanceof BadRequestException) {
            $this->logger->notice('Bad request.', [
                'request_uri' => $this->request->getRequestUri(),
                'request_method' => $this->request->getRequestMethod(),
            ]);
            $this->respondBadRequest();
        } else {
            $this->logger->error(sprintf('%s in %s:%d', $e->getMessage(), $e->getFile(), $e->getLine()));
            $this->respondGeneralError($e);
        }
    }

    /**
     * Responds to client in case of a "400 bad request" http error.
     *
     * @return void
     */
    protected function respondBadRequest(): void
    {
        $responder = $this->provideResponder();
        $responder->badRequest();
        $responder->respond();
    }

    /**
     * Responds to client in case of a "404 not found" http error.
     *
     * @return void
     */
    protected function respondNotFound(): void
    {
        $responder = $this->provideResponder();
        $responder->notFound();
        $responder->respond();
    }

    /**
     * Responds to client in case of a "405 method not allowed" http error.
     *
     * @return void
     */
    protected function respondMethodNotAllowed(): void
    {
        $responder = $this->provideResponder();
        $responder->methodNotAllowed();
        $responder->respond();
    }

    /**
     * Responds to client in case of a general server error (500).
     *
     * @param \Exception $e
     * @return void
     */
    protected function respondGeneralError(\Exception $e): void
    {
        $responder = $this->provideResponder();
        $responder->error([
            'Message' => $e->getMessage(),
            'File' => $e->getFile(),
            'Line' => $e->getLine(),
        ]);
        $responder->respond();
    }

    /**
     * Responds to client in case of a PHP error.
     *
     * @param \Error $e
     */
    protected function respondPhpError(\Error $e): void
    {
        $responder = $this->provideResponder();
        $responder->error([
            'Message' => $e->getMessage(),
            'File' => $e->getFile(),
            'Line' => $e->getLine(),
        ]);
        $responder->respond();
    }

    /**
     * Provides a responder according to requested content type.
     *
     * @return ResponderInterface
     */
    protected function provideResponder(): ResponderInterface
    {
        if ($this->request->getContentType() === 'application/json') {
            return new JsonResponder($this->config);
        }
        return new HtmlResponder($this->config);
    }
}
