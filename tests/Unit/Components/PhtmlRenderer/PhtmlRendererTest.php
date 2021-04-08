<?php

namespace Bloatless\Endocore\Tests\Integration;

use Bloatless\Endocore\Components\PhtmlRenderer\Factory;
use Bloatless\Endocore\Components\PhtmlRenderer\PhtmlRenderer;
use Bloatless\Endocore\Components\PhtmlRenderer\TemplatingException;
use PHPUnit\Framework\TestCase;

class PhtmlRendererTest extends TestCase
{
    public $config;

    public $factory;

    public function setUp(): void
    {
        $this->config = [
            'path_views' => TESTS_ROOT . '/Fixtures/resources/views',
        ];
        $this->factory = new Factory($this->config);
    }

    public function testGetSetPathViews()
    {
        $renderer = new PhtmlRenderer;
        // test without tailing slash
        $renderer->setPathViews($this->config['path_views']);
        $this->assertEquals($this->config['path_views'], $renderer->getPathViews());

        // test with tailing slash
        $renderer->setPathViews($this->config['path_views'] . '/');
        $this->assertEquals($this->config['path_views'], $renderer->getPathViews());
    }

    public function testGetPathView()
    {
        $renderer = new PhtmlRenderer;
        $renderer->setPathViews($this->config['path_views']);
        $pathView = $renderer->getPathView('simple_view');
        $this->assertEquals($this->config['path_views'] . '/simple_view.phtml', $pathView);
    }

    public function testGetSetLayout()
    {
        $renderer = new PhtmlRenderer;
        $renderer->setLayout('default');
        $this->assertEquals('default', $renderer->getLayout());
    }

    public function testGetSetView()
    {
        $renderer = new PhtmlRenderer;
        $renderer->setView('simple_view');
        $this->assertEquals('simple_view', $renderer->getView());
    }

    public function testAssign()
    {
        $renderer = new PhtmlRenderer;
        $renderer->assign(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $renderer->getTemplateVariables());

        $renderer->assign(['a' => 'b']);
        $this->assertEquals(['foo' => 'bar', 'a' => 'b'], $renderer->getTemplateVariables());

        $renderer->assign(['foo' => 'baz']);
        $templateVars = $renderer->getTemplateVariables();
        $this->assertEquals('baz', $templateVars['foo']);
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
