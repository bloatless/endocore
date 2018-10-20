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
}
