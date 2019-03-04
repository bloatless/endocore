<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\Router;

interface RouterInterface
{
    const NOT_FOUND = 0;
    const FOUND = 1;
    const METHOD_NOT_ALLOWED = 2;

    /**
     * Dispatches HTTP request and returns route information.
     *
     * @param string $httpMethod
     * @param string $uri
     * @return array
     */
    public function dispatch(string $httpMethod, string $uri): array;
}
