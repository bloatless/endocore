<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Interfaces;

interface RouterInterface
{
    const NOT_FOUND = 0;
    const FOUND = 1;
    const METHOD_NOT_ALLOWED = 2;

    public function dispatch(string $httpMethod, string $uri) : array;
}
