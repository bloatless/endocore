<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Exception;

use Bloatless\Endocore\Exception\Http\BadRequestException;
use Bloatless\Endocore\Exception\Http\MethodNotAllowedException;
use Bloatless\Endocore\Exception\Http\NotFoundException;
use Bloatless\Endocore\Http\Response;
use Bloatless\Endocore\Components\Logger\LoggerInterface;
use Bloatless\Endocore\Http\Request;
use Bloatless\Endocore\Responder\HtmlResponder;
use Bloatless\Endocore\Responder\JsonResponder;
use Bloatless\Endocore\Responder\ResponderInterface;

class ExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @var array $config
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

    public function __construct(array $config, LoggerInterface $logger, Request $request)
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->request = $request;
    }

    /**
     * Handles internal php errors.
     *
     * @param \Error $e
     * @return Response
     */
    public function handleError(\Error $e): Response
    {
        $this->logger->error(sprintf('%s in %s:%d', $e->getMessage(), $e->getFile(), $e->getLine()));
        return $this->providePhpErrorResponse($e);
    }

    /**
     * Handles exceptions thrown by application.
     *
     * @param \Exception $e
     * @return Response
     */
    public function handleException(\Exception $e): Response
    {
        if ($e instanceof NotFoundException) {
            $this->logger->info('404 Not Found: ' . $this->request->getRequestUri());
            $response = $this->provideNotFoundResponse();
        } elseif ($e instanceof MethodNotAllowedException) {
            $this->logger->notice('Method not allowed.', [
                'request_uri' => $this->request->getRequestUri(),
                'request_method' => $this->request->getRequestMethod(),
            ]);
            $response = $this->provideMethodNotAllowedResponse();
        } elseif ($e instanceof BadRequestException) {
            $this->logger->notice('Bad request.', [
                'request_uri' => $this->request->getRequestUri(),
                'request_method' => $this->request->getRequestMethod(),
            ]);
            $response = $this->provideBadRequestResponse();
        } else {
            $this->logger->error(sprintf('%s in %s:%d', $e->getMessage(), $e->getFile(), $e->getLine()));
            $response = $this->provideGeneralErrorResponse($e);
        }

        return $response;
    }

    /**
     * Responds to client in case of a "400 bad request" http error.
     *
     * @return Response
     */
    protected function provideBadRequestResponse(): Response
    {
        $responder = $this->provideResponder();
        return $responder->badRequest();
    }

    /**
     * Responds to client in case of a "404 not found" http error.
     *
     * @return Response
     */
    protected function provideNotFoundResponse(): Response
    {
        $responder = $this->provideResponder();
        return $responder->notFound();
    }

    /**
     * Responds to client in case of a "405 method not allowed" http error.
     *
     * @return Response
     */
    protected function provideMethodNotAllowedResponse(): Response
    {
        $responder = $this->provideResponder();
        return $responder->methodNotAllowed();
    }

    /**
     * Responds to client in case of a general server error (500).
     *
     * @param \Exception $e
     * @return Response
     */
    protected function provideGeneralErrorResponse(\Exception $e): Response
    {
        $responder = $this->provideResponder();
        return $responder->error([
            'Message' => $e->getMessage(),
            'File' => $e->getFile(),
            'Line' => $e->getLine(),
        ]);
    }

    /**
     * Responds to client in case of a PHP error.
     *
     * @param \Error $e
     * @return Response
     */
    protected function providePhpErrorResponse(\Error $e): Response
    {
        $responder = $this->provideResponder();
        return $responder->error([
            'Message' => $e->getMessage(),
            'File' => $e->getFile(),
            'Line' => $e->getLine(),
        ]);
    }

    /**
     * Provides a responder according to requested content type.
     *
     * @return ResponderInterface
     */
    protected function provideResponder(): ResponderInterface
    {
        $acceptHeader = $this->request->getServerParam('HTTP_ACCEPT', '');
        if (strpos($acceptHeader, 'application/json') !== false) {
            return new JsonResponder($this->config);
        }
        return new HtmlResponder($this->config);
    }
}
