<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Logger;

class NullLogger extends AbstractLogger
{
    public function log(string $level, string $message, array $context = []): void
    {
        return;
    }
}
