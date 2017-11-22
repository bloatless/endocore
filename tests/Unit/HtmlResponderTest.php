<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Exceptions\Application\ClassNotFoundException;
use Nekudo\ShinyCore\HtmlResponder;
use Nekudo\ShinyCore\PhtmlRenderer;
use PHPUnit\Framework\TestCase;

class HtmlResponderTest extends TestCase
{
    public $configData;

    public function setUp()
    {
        $this->configData = include __DIR__ . '/../Mocks/config.php';
    }

    public function testInitWithDefaultRenderer()
    {
        $configData = $this->configData;
        unset($configData['renderer']);
        $config = (new Config)->fromArray($configData);
        $responder = new HtmlResponder($config);
        $renderer = $responder->getRenderer();
        $this->assertInstanceOf(PhtmlRenderer::class, $renderer);
    }

    public function testInitWithRendererSetInConfig()
    {
        $config = (new Config)->fromArray($this->configData);
        $responder = new HtmlResponder($config);
        $renderer = $responder->getRenderer();
        $this->assertInstanceOf(PhtmlRenderer::class, $renderer);
    }

    public function testInitWithInvalidRendererSetInConfig()
    {
        $this->expectException(ClassNotFoundException::class);
        $configData = $this->configData;
        $configData['renderer'] = '\Nekudo\Invalid\Renderer';
        $config = (new Config)->fromArray($configData);
        $responder = new HtmlResponder($config);
    }

    public function testGetSetRenderer()
    {
        $config = (new Config)->fromArray($this->configData);
        $responder = new HtmlResponder($config);
        $responder->setRenderer(new PhtmlRenderer);
        $this->assertInstanceOf(PhtmlRenderer::class, $responder->getRenderer());
    }
}
