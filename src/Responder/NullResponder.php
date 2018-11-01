<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Responder;

use Nekudo\ShinyCore\Config;

class NullResponder implements ResponderInterface
{
    public function __construct(Config $config)
    {
    }

    public function setStatus(int $statusCode): void
    {
        return;
    }

    public function setBody(string $body): void
    {
        return;
    }

    public function found(array $data): void
    {
        return;
    }

    public function badRequest(): void
    {
        return;
    }

    public function notFound(): void
    {
        return;
    }

    public function methodNotAllowed(): void
    {
        return;
    }

    public function error(array $errors): void
    {
        return;
    }

    public function respond(): void
    {
        return;
    }
}
