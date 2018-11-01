<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Logger;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Exceptions\Application\ShinyCoreException;

class FileLogger extends AbstractLogger
{
    /**
     * @var string $logsDir
     */
    private $logsDir;

    /**
     * @param Config $config
     * @throws ShinyCoreException
     */
    public function __construct(Config $config)
    {
        $this->setLogsDir($config->getPath('logs', ''));
    }

    /**
     * Sets path to directory containing log files.
     *
     * @param string $logsDir
     * @throws ShinyCoreException
     */
    public function setLogsDir(string $logsDir): void
    {
        if (!file_exists($logsDir) || !is_writable($logsDir)) {
            throw new ShinyCoreException('Logs path does not exist or is not writable.');
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
        // do not log if level is below min level:
        if ($this->isHandling($level) === false) {
            return;
        }

        $pathToLogfile = $this->openLogfile();
        $lineToLog = sprintf('[ %s ] %s: %s', date('Y-m-d H:i:s'), $level, $message) . PHP_EOL;
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
        return date('Y-m-d') . '_shinycore.log';
    }
}
