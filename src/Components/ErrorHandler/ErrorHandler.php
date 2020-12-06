<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\ErrorHandler;

use Bloatless\Endocore\Components\Http\Exception\BadRequestException;
use Bloatless\Endocore\Components\Http\Exception\MethodNotAllowedException;
use Bloatless\Endocore\Components\Http\Exception\NotFoundException;
use Bloatless\Endocore\Contracts\ErrorHandler\ErrorHandler as ErrorHandlerContract;
use Bloatless\Endocore\Contracts\Logger\Logger as LoggerContract;
use Bloatless\Endocore\Domain\Payload;
use Bloatless\Endocore\Http\Response;
use Bloatless\Endocore\Http\Request;
use Bloatless\Endocore\Responder\HtmlResponder;
use Bloatless\Endocore\Responder\JsonResponder;
use Bloatless\Endocore\Responder\Responder;

class ErrorHandler implements ErrorHandlerContract
{
    /**
     * @var LoggerContract $logger
     */
    protected LoggerContract $logger;

    /**
     * @var Request $request
     */
    protected Request $request;

    public function __construct(LoggerContract $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Handles user errors triggered by phps "trigger_error" method.
     *
     * @param int $level
     * @param string $message
     * @param string $filename
     * @param int $line
     * @return bool
     * @throws \ErrorException
     */
    public function handleError(int $level, string $message, string $filename, int $line): bool
    {
        switch ($level) {
            case E_USER_DEPRECATED:
            case E_USER_NOTICE:
                $this->logger->notice($message, [
                    'File' => $filename,
                    'Line' => $line,
                ]);

                return true;
            case E_USER_WARNING:
                $this->logger->warning($message, [
                    'File' => $filename,
                    'Line' => $line,
                ]);

                return true;
            case E_USER_ERROR:
            default:
                // in case of user-error or we throw an exception and pass processing to the handleException method
                throw new \ErrorException($message, 0, $level, $filename, $line);
        }
    }

    /**
     * Handles all exceptions thrown by php or user.
     *
     * @param \Throwable $e
     * @return void
     */
    public function handleException(\Throwable $e): void
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
            $response = $this->provideErrorResponse($e);
        }

        $this->send($response);
        exit;
    }

    /**
     * Injects a request object.
     *
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * Responds to client in case of a "400 bad request" http error.
     *
     * @return Response
     */
    protected function provideBadRequestResponse(): Response
    {
        $responder = $this->provideResponder();
        $payload = new Payload(Payload::STATUS_BAD_REQUEST);

        return $responder->__invoke($this->request, $payload);
    }

    /**
     * Responds to client in case of a "404 not found" http error.
     *
     * @return Response
     */
    protected function provideNotFoundResponse(): Response
    {
        $responder = $this->provideResponder();
        $payload = new Payload(Payload::STATUS_NOT_FOUND);

        return $responder->__invoke($this->request, $payload);
    }

    /**
     * Responds to client in case of a "405 method not allowed" http error.
     *
     * @return Response
     */
    protected function provideMethodNotAllowedResponse(): Response
    {
        $responder = $this->provideResponder();
        $payload = new Payload(Payload::STATUS_METHOD_NOT_ALLOWED);

        return $responder->__invoke($this->request, $payload);
    }

    /**
     * Responds to client in case of a general server error (500).
     *
     * @param \Throwable $e
     * @return Response
     */
    protected function provideErrorResponse(\Throwable $e): Response
    {
        $responder = $this->provideResponder();
        $payload = new Payload(Payload::STATUS_ERROR, [
            'Message' => $e->getMessage(),
            'File' => $e->getFile(),
            'Line' => $e->getLine(),
        ]);

        return $responder->__invoke($this->request, $payload);
    }

    /**
     * Provides a responder according to requested content type.
     *
     * @return Responder
     */
    protected function provideResponder(): Responder
    {
        $acceptHeader = $this->request->getServerParam('HTTP_ACCEPT', '');
        if (strpos($acceptHeader, 'application/json') !== false) {
            return new JsonResponder();
        }

        return new HtmlResponder();
    }

    protected function send(Response $response): void
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
