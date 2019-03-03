<?php

namespace Bloatless\Endocore\Tests\Unit;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Exception\Application\EndocoreException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private $pathToConfigfile;

    public function setUp()
    {
        $this->pathToConfigfile = SC_TESTS . '/Fixtures/config.php';
    }

    public function testFromFile()
    {
        $config = (new Config)->fromFile($this->pathToConfigfile);
        $this->assertInstanceOf(Config::class, $config);
        $this->assertNotEmpty($config->getClass('html_renderer'));
        $this->assertNotEmpty($config->getPath('logs'));
        $this->assertNotEmpty($config->getDefaultDbConfig());

        $this->expectException(EndocoreException::class);
        $config = (new Config)->fromFile('/invalid/path');
    }

    public function testFromArray()
    {
        $configData = include $this->pathToConfigfile;
        $config = (new Config)->fromArray($configData);
        $this->assertInstanceOf(Config::class, $config);
        $this->assertNotEmpty($config->getClass('html_renderer'));
        $this->assertNotEmpty($config->getPath('logs'));
        $this->assertNotEmpty($config->getDefaultDbConfig());
    }

    public function testSetGetClass()
    {
        $config = new Config;
        $config->setClass('foo', 'Bar');
        $this->assertEquals('Bar', $config->getClass('foo'));
        $this->assertEquals('Baz', $config->getClass('xxx', 'Baz'));
    }

    public function testSetGetPath()
    {
        $config = new Config;
        $config->setPath('foo', 'bar');
        $this->assertEquals('bar', $config->getPath('foo'));
        $this->assertEquals('baz', $config->getPath('xxx', 'baz'));
    }

    public function testSetGetDbConfig()
    {
        $config = new Config;
        $config->addDbConfig('foo', ['host' => 'localhost']);
        $this->assertArrayHasKey('host', $config->getDbConfig('foo'));

        $this->expectException(\InvalidArgumentException::class);
        $config->getDbConfig('bar');
    }

    public function testSetGetDefaultDatabase()
    {
        $config = (new Config)->fromFile($this->pathToConfigfile);
        $this->assertEquals('db1', $config->getDefaultDatabase());

        $this->expectException(\InvalidArgumentException::class);
        $config->setDefaultDatabase('bar');

        $config->addDbConfig('bar', ['host' => 'bar']);
        $this->assertArrayHasKey('host', $config->getDbConfig('bar'));
    }

    public function testGetDefaultDbConfig()
    {
        $config = (new Config)->fromFile($this->pathToConfigfile);
        $this->assertArrayHasKey('host', $config->getDefaultDbConfig());

        $config = new Config;
        $this->expectException(EndocoreException::class);
        $config->getDefaultDbConfig();
    }

    public function testSetGetMinLogLevel()
    {
        $config = (new Config)->fromFile($this->pathToConfigfile);
        $this->assertEquals('debug', $config->getMinLogLevel());
        $config->setMinLogLevel('error');
        $this->assertEquals('error', $config->getMinLogLevel());
    }
}
