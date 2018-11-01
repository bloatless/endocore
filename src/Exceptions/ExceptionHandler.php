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
        // @todo Implement error output and logging
        var_dump($e);
    }

    /**
     * Handles exceptions thrown by application.
     *
     * @param \Exception $e
     */
    public function handleException(\Exception $e): void
    {
        // @todo Log errors

        if ($e instanceof NotFoundException) {
            $this->respondNotFound();
        } elseif ($e instanceof MethodNotAllowedException) {
            $this->respondMethodNotAllowed();
        } elseif ($e instanceof BadRequestException) {
            $this->respondBadRequest();
        } else {
            $this->respondGeneralError($e);
        }
    }

    protected function respondBadRequest(): void
    {
        $responder = $this->provideResponder();
        $responder->badRequest();
        $responder->respond();
    }

    protected function respondNotFound(): void
    {
        $responder = $this->provideResponder();
        $responder->notFound();
        $responder->respond();
    }

    protected function respondMethodNotAllowed(): void
    {
        $responder = $this->provideResponder();
        $responder->methodNotAllowed();
        $responder->respond();
    }

    protected function respondGeneralError(\Exception $e): void
    {
        $responder = $this->provideResponder();
        $responder->error([$e->getMessage()]);
        $responder->respond();
    }

    protected function provideResponder(): ResponderInterface
    {
        if ($this->request->getContentType() === 'application/json') {
            return new JsonResponder($this->config);
        }
        return new HtmlResponder($this->config);
    }
}
