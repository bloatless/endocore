<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Responder;

use Bloatless\Endocore\Http\Response;

interface ResponderInterface
{
    public function __construct(array $config);

    /**
     * Prepares a "HTTP 200 Found" response.
     *
     * @param array $data
     * @return Response
     */
    public function found(array $data): Response;

    /**
     * Prepares a "HTTP 400 Bad Request" response.
     *
     * @return Response
     */
    public function badRequest(): Response;

    /**
     * Prepares a "HTTP 404 Not Found" response.
     *
     * @return Response
     */
    public function notFound(): Response;

    /**
     * Prepares a "HTTP 405 Method not found" response.
     *
     * @return Response
     */
    public function methodNotAllowed(): Response;

    /**
     * Prepares a "HTTP 500 Internal Server Error" response.
     *
     * @param array $errors
     * @return Response
     */
    public function error(array $errors): Response;

    /**
     * Returns the response object.
     *
     * @return Response
     */
    public function getResponse(): Response;
}
