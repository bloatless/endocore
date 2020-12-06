<?php

namespace Bloatless\Endocore\Tests\Unit\Logger;

use Bloatless\Endocore\Components\Logger\Factory;
use Bloatless\Endocore\Components\Logger\LoggerException;
use Bloatless\Endocore\Components\Logger\FileLogger;
use Bloatless\Endocore\Components\Logger\LogLevel;
use PHPUnit\Framework\TestCase;

class FileLoggerTest extends TestCase
{
    /** @var array $config */
    public $config;

    public $factory;

    public function setUp(): void
    {
        $this->config = include TESTS_ROOT . '/Fixtures/config.php';
        $this->factory = new Factory($this->config);
    }

    public function testInitWithValidLogPath()
    {
        $logger = $this->factory->makeFileLogger();
        $this->assertInstanceOf(FileLogger::class, $logger);
    }

    public function testWithoutLogPath()
    {
        $config = include TESTS_ROOT . '/Fixtures/config.php';
        unset($config['logger']['path_logs']);
        $factory = new Factory($config);
        $this->expectException(LoggerException::class);
        $logger = $factory->makeFileLogger();
    }

    public function testInitWithInvalidLogPath()
    {
        $config = include TESTS_ROOT . '/Fixtures/config.php';
        $config['logger']['path_logs'] = 'foo';
        $factory = new Factory($config);
        $this->expectException(LoggerException::class);
        $logger = $factory->makeFileLogger();
    }

    public function testLogfileIsOpened()
    {
        $pathToLogfile = $this->providePathToLogfile();
        $logger = $this->factory->makeFileLogger();
        $logger->log(LogLevel::DEBUG, 'foobar');
        $this->assertFileExists($pathToLogfile);
        unlink($pathToLogfile);
    }

    public function testLogsAtAllLevels()
    {
        $logger = $this->factory->makeFileLogger();
        $pathToLogfile = $this->providePathToLogfile();
        $messages = $this->provideLevelsAndMessages();
        foreach ($messages as $level => $message) {
            $logger->{$level}($message);
        }
        $logfileContent = file_get_contents($pathToLogfile);
        foreach ($messages as $level => $message) {
            $expected = sprintf('%s: %s', ucfirst($level), $message);
            $this->assertTrue(strpos($logfileContent, $expected) !== false);
        }
        unlink($pathToLogfile);
    }

    public function testLogsWithoutContext()
    {
        $logger = $this->factory->makeFileLogger();
        $pathToLogfile = $this->providePathToLogfile();
        $logger->log(LogLevel::ERROR, 'foobar');
        $logfileContent = file_get_contents($pathToLogfile);
        $this->assertFalse(strpos($logfileContent, '--- Context ---') !== false);
        $this->assertTrue(strpos($logfileContent, 'foobar') !== false);
        unlink($pathToLogfile);
    }

    public function testLogsWithContext()
    {
        $logger = $this->factory->makeFileLogger();
        $pathToLogfile = $this->providePathToLogfile();
        $logger->log(LogLevel::ERROR, 'foobar', ['from' => 'unit test']);
        $logfileContent = file_get_contents($pathToLogfile);
        $this->assertTrue(strpos($logfileContent, '--- Context ---') !== false);
        $this->assertTrue(strpos($logfileContent, '[from] => unit test') !== false);
        unlink($pathToLogfile);
    }

    public function testDoesNotLogInvalidLevel()
    {
        $logger = $this->factory->makeFileLogger();
        $this->expectException(\InvalidArgumentException::class);
        $logger->log('foo', 'bar');
    }

    public function testGetLevels()
    {
        $logger = $this->factory->makeFileLogger();
        $levels = $logger->getLevels();
        $this->assertTrue(is_array($levels));
        $this->assertTrue(count($levels) === 8);
    }

    public function testGetLevelCode()
    {
        $logger = $this->factory->makeFileLogger();
        $this->assertEquals(0, $logger->getLevelCode(LogLevel::DEBUG));
        $this->assertEquals(7, $logger->getLevelCode(LogLevel::EMERGENCY));
        $this->expectException(\InvalidArgumentException::class);
        $logger->getLevelCode('foo');
    }

    public function testSetGetMinLogLevel()
    {
        $logger = $this->factory->makeFileLogger();
        $logger->setMinLevel(LogLevel::NOTICE);
        $this->assertEquals(LogLevel::NOTICE, $logger->getMinLevel());
        $this->expectException(\InvalidArgumentException::class);
        $logger->setMinLevel('foo');
    }

    public function testRespectsMinLevel()
    {
        $pathToLogfile = $this->providePathToLogfile();
        $logger = $this->factory->makeFileLogger();
        $logger->setMinLevel(LogLevel::WARNING);
        $logger->debug('foobar');
        $this->assertFileNotExists($pathToLogfile);
        $logger->emergency('barfoo');
        $this->assertFileExists($pathToLogfile);
        unlink($pathToLogfile);
    }

    public function testLevelIsValid()
    {
        $logger = $this->factory->makeFileLogger();
        $this->assertTrue($logger->levelIsValid(LogLevel::WARNING));
        $this->assertFalse($logger->levelIsValid('invalid level'));
    }

    private function provideLevelsAndMessages(): array
    {
        return [
            LogLevel::EMERGENCY => 'Test message of level emergency.',
            LogLevel::ALERT => 'Test message of level alert.',
            LogLevel::CRITICAL => 'Test message of level critical.',
            LogLevel::ERROR => 'Test message of level error.',
            LogLevel::WARNING => 'Test message of level warning.',
            LogLevel::NOTICE => 'Test message of level notice.',
            LogLevel::INFO => 'Test message of level info.',
            LogLevel::DEBUG => 'Test message of level debug.',
        ];
    }

    private function providePathToLogfile(): string
    {
        $pathToLogfile = $this->config['logger']['path_logs'];
        $pathToLogfile = rtrim($pathToLogfile, '/') . '/';
        $pathToLogfile .= date('Y-m-d') . '_endocore.log';
        return $pathToLogfile;
    }
}
