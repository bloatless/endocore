<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\Logger;

class NullLogger extends AbstractLogger
{
    /**
     * Dummy logger.
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log(string $level, string $message, array $context = []): void
    {
        return;
    }
}
