<?php

namespace Bloatless\Endocore\Tests\Unit\Components\PhtmlRenderer;

use Bloatless\Endocore\Components\PhtmlRenderer\Factory;
use Bloatless\Endocore\Components\PhtmlRenderer\PhtmlRenderer;
use Bloatless\Endocore\Components\PhtmlRenderer\TemplatingException;
use PHPUnit\Framework\TestCase;

class PhtmlRendererTest extends TestCase
{
    public $factory;

    public function setUp(): void
    {
        $config = include TESTS_ROOT . '/Fixtures/config.php';
        $this->factory = new Factory($config['renderer']);
    }

    public function testRenderSimpleView()
    {
        $renderer = $this->factory->makeRenderer();
        $out = $renderer->render('simple_view', ['mock' => 'Hello World!']);
        $this->assertEquals('Hello World!', $out);
    }

    public function testRenderViewWithHtmlEntities()
    {
        $renderer = $this->factory->makeRenderer();
        $out = $renderer->render('simple_view', ['mock' => 'foo & bar']);
        $this->assertEquals('foo &amp; bar', $out);
    }

    public function testRenderViewWithLayout()
    {
        $renderer = $this->factory->makeRenderer();
        $out = $renderer->render('layout_view', ['mock' => 'Hallo Layout!']);
        $out = trim($out);
        $this->assertEquals('Hallo Layout!', $out);
    }

    public function testRenderInvalidView()
    {
        $renderer = $this->factory->makeRenderer();
        $this->expectException(TemplatingException::class);
        $renderer->render('foobar');
    }

    public function testRenderViewWithInvalidLayout()
    {
        $renderer = $this->factory->makeRenderer();
        $this->expectException(TemplatingException::class);
        $renderer->render('invalid_layout_view', ['mock' => 'foo']);
    }

    public function testRenderWithInclude()
    {
        $renderer = $this->factory->makeRenderer();
        $out = $renderer->render('view_with_include', []);
        $this->assertStringContainsString('included content', $out);
    }

    public function testRenderWithIncludeWithData()
    {
        $renderer = $this->factory->makeRenderer();
        $out = $renderer->render('view_with_include_and_data', []);
        $this->assertStringContainsString('bar', $out);
    }
}
