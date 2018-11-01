<?php

namespace Nekudo\ShinyCore\Tests\Mocks;

use Nekudo\ShinyCore\Responder\HttpResponder;

class HttpResponderStub extends HttpResponder
{
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
}
