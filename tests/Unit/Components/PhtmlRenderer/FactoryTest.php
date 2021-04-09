<?php

namespace Bloatless\Endocore\Tests\Unit\Components\PhtmlRenderer;

use Bloatless\Endocore\Components\PhtmlRenderer\Factory;
use Bloatless\Endocore\Components\PhtmlRenderer\PhtmlRenderer;
use Bloatless\Endocore\Components\PhtmlRenderer\TemplatingException;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testWithValidConfig()
    {
        $pathViews = TESTS_ROOT . '/Fixtures/resources/views';
        $config = include TESTS_ROOT . '/Fixtures/config.php';
        $factory = new Factory($config['renderer']);
        $renderer = $factory->makeRenderer();
        $this->assertInstanceOf(PhtmlRenderer::class, $renderer);
    }

    public function testWithInvalidConfig()
    {
        $factory = new Factory([]);
        $this->expectException(TemplatingException::class);
        $renderer = $factory->makeRenderer();
    }
}
