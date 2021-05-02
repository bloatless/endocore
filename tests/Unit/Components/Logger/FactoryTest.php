<?php

namespace Bloatless\Endocore\Tests\Unit\Logger;

use Bloatless\Endocore\Components\Core\Logger\LoggerFactory;
use Bloatless\Endocore\Components\Core\Logger\FileLogger;
use Bloatless\Endocore\Components\Core\Logger\LoggerException;
use Bloatless\Endocore\Components\Core\Logger\NullLogger;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /** @var array $config */
    public $config;

    public function setUp(): void
    {
        $this->config = include TESTS_ROOT . '/Fixtures/config.php';
    }

    public function testGetFileLogger()
    {
        $factory = new LoggerFactory($this->config['logger']);
        $logger = $factory->makeFileLogger();
        $this->assertInstanceOf(FileLogger::class, $logger);
    }

    public function testGetFileLoggerWithInvalidConfig()
    {
        $factory = new LoggerFactory([]);
        $this->expectException(LoggerException::class);
        $factory->makeFileLogger();
    }

    public function testGetNullLogger()
    {
        $factory = new LoggerFactory($this->config['logger']);
        $logger = $factory->makeNullLogger();
        $this->assertInstanceOf(NullLogger::class, $logger);
    }
}