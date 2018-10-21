<?php

namespace Nekudo\ShinyCore\Tests\Integration;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Responder\PhtmlRenderer;
use PHPUnit\Framework\TestCase;

class PhtmlRendererTest extends TestCase
{
    public $config;

    public function setUp()
    {
        $configData = include __DIR__ . '/../Mocks/config.php';
        $this->config = (new Config)->fromArray($configData);
    }

    public function testRenderView()
    {
        $renderer = new PhtmlRenderer($this->config);
        $this->assertInstanceOf(PhtmlRenderer::class, $renderer);
        $out = $renderer->render('simple_view', ['mock' => 'Hello World!']);
        $this->assertEquals('Hello World!', $out);
    }

    public function testRenderViewWithLayout()
    {
        $renderer = new PhtmlRenderer($this->config);
        $this->assertInstanceOf(PhtmlRenderer::class, $renderer);
        $out = $renderer->render('layout_view', ['mock' => 'Hallo Layout!']);
        $this->assertEquals('Hallo Layout!', $out);
    }
}
