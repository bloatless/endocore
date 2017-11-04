<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Exceptions\Application\ClassNotFoundException;
use Nekudo\ShinyCore\HtmlResponder;
use Nekudo\ShinyCore\PhtmlRenderer;
use PHPUnit\Framework\TestCase;

class HtmlResponderTest extends TestCase
{
    public $config;

    public function setUp()
    {
        $this->config = include __DIR__ . '/../Mocks/config.php';
    }

    public function testInitWithDefaultRenderer()
    {
        $config = $this->config;
        unset($config['renderer']);
        $responder = new HtmlResponder($config);
        $renderer = $responder->getRenderer();
        $this->assertInstanceOf(PhtmlRenderer::class, $renderer);
    }

    public function testInitWithRendererSetInConfig()
    {
        $responder = new HtmlResponder($this->config);
        $renderer = $responder->getRenderer();
        $this->assertInstanceOf(PhtmlRenderer::class, $renderer);
    }

    public function testInitWithInvalidRendererSetInConfig()
    {
        $this->expectException(ClassNotFoundException::class);
        $config = $this->config;
        $config['renderer'] = '\Nekudo\Invalid\Renderer';
        $responder = new HtmlResponder($config);
    }

    public function testGetSetRenderer()
    {
        $responder = new HtmlResponder($this->config);
        $responder->setRenderer(new PhtmlRenderer);
        $this->assertInstanceOf(PhtmlRenderer::class, $responder->getRenderer());
    }
}
