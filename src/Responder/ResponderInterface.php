<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Responder;

use Nekudo\ShinyCore\Config;

interface ResponderInterface
{
    public function __construct(Config $config);

    public function setStatus(int $statusCode): void;

    public function setBody(string $body): void;

    public function respond(): void;

    public function found(array $data): void;

    public function badRequest(): void;

    public function notFound(): void;

    public function methodNotAllowed(): void;

    public function error(array $errors): void;
}
