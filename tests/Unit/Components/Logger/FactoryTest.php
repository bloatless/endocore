<?php

namespace Bloatless\Endocore\Tests\Unit\Logger;

use Bloatless\Endocore\Core\Logger\LoggerFactory;
use Bloatless\Endocore\Core\Logger\FileLogger;
use Bloatless\Endocore\Core\Logger\NullLogger;
use Bloatless\Endocore\Exception\Application\EndocoreException;
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
        $factory = new LoggerFactory($this->config);
        $logger = $factory->makeFileLogger();
        $this->assertInstanceOf(FileLogger::class, $logger);
    }

    public function testGetFileLoggerWithInvalidConfig()
    {
        $this->expectException(EndocoreException::class);
        $factory = new LoggerFactory([]);
    }

    public function testGetNullLogger()
    {
        $factory = new LoggerFactory($this->config);
        $logger = $factory->makeNullLogger();
        $this->assertInstanceOf(NullLogger::class, $logger);
    }
}
