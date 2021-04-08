<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Contracts\ErrorHandler;

use Bloatless\Endocore\Components\Http\Request;

interface ErrorHandler
{
    public function handleError(int $level, string $message, string $filename, int $line): bool;

    public function handleException(\Throwable $e): void;

    public function setRequest(Request $request): void;
}
