<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore;

class JsonResponder extends HttpResponder
{
    public function __construct(int $statusCode = 200, string $version = '1.1')
    {
        parent::__construct($statusCode, $version);
        $this->addHeader('Content-Type', 'application/json');
    }

    public function success(array $data): void
    {
        $this->setBody(json_encode(['data' => $data]));
    }

    public function badRequest(array $errors): void
    {
        $this->setStatus(400);
        $this->setBody(json_encode(['errors' => $errors]));
    }

    public function error(array $errors): void
    {
        $this->setStatus(500);
        $this->setBody(json_encode(['errors' => $errors]));
    }
}
