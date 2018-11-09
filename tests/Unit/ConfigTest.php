<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Exception\Application\ShinyCoreException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private $pathToConfigfile;

    public function setUp()
    {
        $this->pathToConfigfile = __DIR__ . '/../Mocks/config.php';
    }

    public function testFromFile()
    {
        $config = (new Config)->fromFile($this->pathToConfigfile);
        $this->assertInstanceOf(Config::class, $config);
        $this->assertTrue(count($config->classes) > 0);
        $this->assertTrue(count($config->paths) >0);

        $this->expectException(ShinyCoreException::class);
        $config = (new Config)->fromFile('/invalid/path');
    }

    public function testFromArray()
    {
        $configData = include $this->pathToConfigfile;
        $config = (new Config)->fromArray($configData);
        $this->assertInstanceOf(Config::class, $config);
        $this->assertTrue(count($config->classes) > 0);
        $this->assertTrue(count($config->paths) >0);
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
}
