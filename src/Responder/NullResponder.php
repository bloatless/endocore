<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Responder;

use Nekudo\ShinyCore\Config;

class NullResponder implements ResponderInterface
{
    public function __construct(Config $config)
    {
    }

    /**
     * Dummy/Null method.
     *
     * @param int $statusCode
     * @return void
     */
    public function setStatus(int $statusCode): void
    {
        return;
    }

    /**
     * Dummy/Null method.
     *
     * @param string $body
     * @return void
     */
    public function setBody(string $body): void
    {
        return;
    }

    /**
     * Dummy/Null method.
     *
     * @param array $data
     * @return void
     */
    public function found(array $data): void
    {
        return;
    }

    /**
     * Dummy/Null method.
     *
     * @return void
     * @return void
     */
    public function badRequest(): void
    {
        return;
    }

    /**
     * Dummy/Null method.
     *
     * @return void
     */
    public function notFound(): void
    {
        return;
    }

    /**
     * Dummy/Null method.
     *
     * @return void
     */
    public function methodNotAllowed(): void
    {
        return;
    }

    /**
     * Dummy/Null method.
     *
     * @param array $errors
     * @return void
     */
    public function error(array $errors): void
    {
        return;
    }

    /**
     * Dummy/Null method.
     *
     * @return void
     */
    public function respond(): void
    {
        return;
    }
}
