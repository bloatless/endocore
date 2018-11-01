<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Exceptions;

interface ExceptionHandlerInterface
{
    public function handleError(\Error $e): void;

    public function handleException(\Exception $e): void;
}
