<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Core\Router;

use Bloatless\Endocore\Contracts\Router\RouteContract;

class Route implements RouteContract
{
    private int $state;

    private string $handler;

    private array $arguments;

    public function __construct(int $state, string $handler = '', array $arguments = [])
    {
        $this->state = $state;
        $this->handler = $handler;
        $this->arguments = $arguments;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }
}
