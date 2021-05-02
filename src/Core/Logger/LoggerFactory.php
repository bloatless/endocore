<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Core\Logger;

use Bloatless\Endocore\Contracts\Components\FactoryContract;
use Bloatless\Endocore\Contracts\Logger\LoggerContract;
use Bloatless\Endocore\Exception\Application\EndocoreException;

class LoggerFactory implements FactoryContract
{
    /**
     * @var array $loggerConfig
     */
    protected array $loggerConfig = [];

    public function __construct(array $config)
    {
        if (!isset($config['logger'])) {
            throw new EndocoreException('Invalid config. Logger key missing.');
        }

        $this->loggerConfig = $config['logger'];
    }

    public function make(): LoggerContract
    {
        return match ($this->loggerConfig['type']) {
            'null' => $this->makeNullLogger(),
            'file' => $this->makeFileLogger(),
            default => throw new EndocoreException(
                sprintf('Unknown logger type (%s). Check config!', $this->loggerConfig['type'])
            )
        };
    }

    /**
     * Creates a new file logger.
     *
     * @return FileLogger
     * @throws LoggerException
     */
    public function makeFileLogger(): FileLogger
    {
        if (empty($this->loggerConfig['path_logs'])) {
            throw new LoggerException('Invalid logger config. "path_logs" is missing.');
        }
        $logger = new FileLogger();
        $logger->setLogsDir($this->loggerConfig['path_logs']);
        $logger->setMinLevel($this->loggerConfig['min_level'] ?? LogLevel::WARNING);

        return $logger;
    }

    /**
     * Creates a new null logger.
     *s
     * @return NullLogger
     */
    public function makeNullLogger(): NullLogger
    {
        return new NullLogger();
    }
}
