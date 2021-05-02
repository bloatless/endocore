<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Core\Logger;

class FileLogger extends AbstractLogger
{
    /**
     * @var string $logsDir
     */
    private string $logsDir;

    /**
     * Sets path to directory containing log files.
     *
     * @param string $logsDir
     * @throws LoggerException()
     */
    public function setLogsDir(string $logsDir): void
    {
        if (!file_exists($logsDir) || !is_writable($logsDir)) {
            throw new LoggerException('Logs path does not exist or is not writable.');
        }
        $this->logsDir = rtrim($logsDir, '/') . '/';
    }

    /**
     * Logs given event to a file.
     *
     * @param string $level
     * @param string $message
     * @param array $context
     */
    public function log(string $level, string $message, array $context = []): void
    {
        if ($this->levelIsValid($level) === false) {
            throw new \InvalidArgumentException('Invalid log-level provided.');
        }

        // do not log if level is below min level:
        if ($this->isHandling($level) === false) {
            return;
        }

        $pathToLogfile = $this->openLogfile();
        $lineToLog = sprintf('[ %s ] %s: %s', date('Y-m-d H:i:s'), ucfirst($level), $message) . PHP_EOL;
        if (!empty($context)) {
            $lineToLog .= '--- Context ---' . PHP_EOL;
            $lineToLog .= print_r($context, true) . PHP_EOL;
        }
        file_put_contents($pathToLogfile, $lineToLog, FILE_APPEND);
    }

    /**
     * Creates new logfile if not yet existing.
     *
     * @return string
     */
    private function openLogfile(): string
    {
        $logfileName = $this->getLogfileName();
        $pathToLogfile = $this->logsDir . $logfileName;
        if (!file_exists($pathToLogfile)) {
            $openedMsg = sprintf('[ %s ] Logfile opened', date('Y-m-d H:i:s')) . PHP_EOL;
            file_put_contents($pathToLogfile, $openedMsg);
        }
        return $pathToLogfile;
    }

    /**
     * Returns logfile name depending on current date.
     *
     * @return string
     */
    private function getLogfileName(): string
    {
        return date('Y-m-d') . '_endocore.log';
    }
}
