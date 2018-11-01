<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Responder;

use Nekudo\ShinyCore\Config;

class JsonResponder extends HttpResponder
{
    public function __construct(Config $config)
    {
        parent::__construct($config);
        $this->addHeader('Content-Type', 'application/json');
    }

    public function found(array $data): void
    {
        $this->setBody(json_encode(['data' => $data]));
    }

    public function badRequest(): void
    {
        $this->setStatus(400);
    }

    public function notFound(): void
    {
        $this->setStatus(404);
    }

    public function methodNotAllowed(): void
    {
        $this->setStatus(405);
    }

    public function error(array $errors): void
    {
        $this->setStatus(500);
        $this->setBody(json_encode(['errors' => $errors]));
    }
}
