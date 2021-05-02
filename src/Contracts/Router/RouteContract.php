<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Contracts\Router;

interface RouteContract
{
    public const NOT_FOUND = 0;
    public const FOUND = 1;
    public const METHOD_NOT_ALLOWED = 2;

    public function getState(): int;

    public function getHandler(): string;

    public function getArguments(): array;
}
