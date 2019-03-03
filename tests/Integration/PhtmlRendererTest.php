<?php

namespace Bloatless\Endocore\Tests\Integration;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Exception\Application\EndocoreException;
use Bloatless\Endocore\Responder\PhtmlRenderer;
use PHPUnit\Framework\TestCase;

class PhtmlRendererTest extends TestCase
{
    public $config;

    public function setUp()
    {
        $configData = include SC_TESTS . '/Fixtures/config.php';
        $this->config = (new Config)->fromArray($configData);
    }

    public function testRenderView()
    {
        $renderer = new PhtmlRenderer($this->config);
        $this->assertInstanceOf(PhtmlRenderer::class, $renderer);
        $out = $renderer->render('simple_view', ['mock' => 'Hello World!']);
        $this->assertEquals('Hello World!', $out);
    }

    public function testRenderInvalidView()
    {
        $renderer = new PhtmlRenderer($this->config);
        $this->expectException(EndocoreException::class);
        $renderer->render('foobar');
    }

    public function testRenderViewWithLayout()
    {
        $renderer = new PhtmlRenderer($this->config);
        $this->assertInstanceOf(PhtmlRenderer::class, $renderer);
        $out = $renderer->render('layout_view', ['mock' => 'Hallo Layout!']);
        $this->assertEquals('Hallo Layout!', $out);
    }

    public function testRenderViewWithInvalidLayout()
    {
        $renderer = new PhtmlRenderer($this->config);
        $this->expectException(EndocoreException::class);
        $renderer->render('invalid_layout_view', ['mock' => 'foo']);
    }
}
