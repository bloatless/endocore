<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Responder;

use Nekudo\ShinyCore\Config;

interface ResponderInterface
{
    public function __construct(Config $config);

    /**
     * Sets the HTTP status code for the response.
     *
     * @param int $statusCode
     * @return void
     */
    public function setStatus(int $statusCode): void;

    /**
     * Sets the HTTP response body.
     *
     * @param string $body
     * @return void
     */
    public function setBody(string $body): void;

    /**
     * Executes the actual response to the client.
     *
     * @return void
     */
    public function respond(): void;

    /**
     * Prepares a "HTTP 200 Found" response.
     *
     * @param array $data
     * @return void
     */
    public function found(array $data): void;

    /**
     * Prepares a "HTTP 400 Bad Request" response.
     *
     * @return void
     */
    public function badRequest(): void;

    /**
     * Prepares a "HTTP 404 Not Found" response.
     *
     * @return void
     */
    public function notFound(): void;

    /**
     * Prepares a "HTTP 405 Method not found" response.
     *
     * @return void
     */
    public function methodNotAllowed(): void;

    /**
     * Prepares a "HTTP 500 Internal Server Error" response.
     *
     * @param array $errors
     * @return void
     */
    public function error(array $errors): void;
}
