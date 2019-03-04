<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\Logger;

/**
 * This is a simple Logger implementation that other Loggers can inherit from.
 *
 * It simply delegates all log-level-specific methods to the `log` method to
 * reduce boilerplate code that a simple Logger that does the same thing with
 * messages regardless of the error level has to implement.
 */
abstract class AbstractLogger implements LoggerInterface
{
    /**
     * @var array $levels
     */
    protected $levels = [
        0 => LogLevel::DEBUG,
        1 => LogLevel::INFO,
        2 => LogLevel::NOTICE,
        3 => LogLevel::WARNING,
        4 => LogLevel::ERROR,
        5 => LogLevel::CRITICAL,
        6 => LogLevel::ALERT,
        7 => LogLevel::EMERGENCY,
    ];

    /**
     * @var int $minLevel
     */
    protected $minLevel = 0;

    /**
     * Returns log levels with numeric index.
     *
     * @return array
     */
    public function getLevels(): array
    {
        return $this->levels;
    }

    /**
     * Sets min. log-level.
     *
     * @param string $level
     */
    public function setMinLevel(string $level): void
    {
        if ($this->levelIsValid($level) === false) {
            throw new \InvalidArgumentException('Invalid log-level provided.');
        }
        $this->minLevel = $this->getLevelCode($level);
    }

    /**
     * Returns min. log-level.
     *
     * @return string
     */
    public function getMinLevel(): string
    {
        return $this->levels[$this->minLevel];
    }

    /**
     * Checks if log level is handled by logger.
     *
     * @param string $level
     * @return bool
     */
    public function isHandling(string $level): bool
    {
        $levelCode = $this->getLevelCode($level);
        return $levelCode >= $this->minLevel;
    }

    /**
     * Returns numeric level code for given log-level.
     *
     * @param string $level
     * @return int
     */
    public function getLevelCode(string $level): int
    {
        if ($this->levelIsValid($level) === false) {
            throw new \InvalidArgumentException('Invalid log-level provided.');
        }
        return array_search($level, $this->levels);
    }

    /**
     * Checks if log-level is valid.
     *
     * @param string $level
     * @return bool
     */
    public function levelIsValid(string $level): bool
    {
        return in_array($level, $this->levels);
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency(string $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function alert(string $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function critical(string $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error(string $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function warning(string $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice(string $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function info(string $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug(string $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }
}
