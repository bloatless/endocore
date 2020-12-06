<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Contracts\Router;

interface Router
{
    /**
     * Dispatches HTTP request and returns route information.
     *
     * @param string $httpMethod
     * @param string $uri
     * @return array
     */
    public function dispatch(string $httpMethod, string $uri): Route;
}
