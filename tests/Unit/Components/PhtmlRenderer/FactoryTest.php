<?php

namespace Bloatless\Endocore\Components\PhtmlRenderer\Tests\Unit;

use Bloatless\Endocore\Components\PhtmlRenderer\Factory;
use Bloatless\Endocore\Components\PhtmlRenderer\PhtmlRenderer;
use Bloatless\Endocore\Components\PhtmlRenderer\TemplatingException;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testWithValidConfig()
    {
        $pathViews = TESTS_ROOT . '/Fixtures/resources/views';
        $config = [
            'path_views' => $pathViews,
        ];
        $factory = new Factory($config);
        $renderer = $factory->makeRenderer();
        $this->assertInstanceOf(PhtmlRenderer::class, $renderer);
        $this->assertEquals($pathViews, $renderer->getPathViews());
    }

    public function testWithInvalidConfig()
    {
        $factory = new Factory([]);
        $this->expectException(TemplatingException::class);
        $renderer = $factory->makeRenderer();
    }
}
