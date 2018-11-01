<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Exceptions\Application\ShinyCoreException;
use Nekudo\ShinyCore\Responder\HtmlResponder;
use Nekudo\ShinyCore\Responder\PhtmlRenderer;
use PHPUnit\Framework\TestCase;

class HtmlResponderTest extends TestCase
{
    public $configData;

    public $config;

    public function setUp()
    {
        $this->configData = include __DIR__ . '/../Mocks/config.php';
        $this->config = (new Config)->fromArray($this->configData);
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
        $responder = new HtmlResponder($this->config);
        $renderer = $responder->getRenderer();
        $this->assertInstanceOf(PhtmlRenderer::class, $renderer);
    }

    public function testInitWithInvalidRendererSetInConfig()
    {
        $this->expectException(ShinyCoreException::class);
        $configData = $this->configData;
        $configData['classes']['html_renderer'] = '\Nekudo\Invalid\Renderer';
        $config = (new Config)->fromArray($configData);
        $responder = new HtmlResponder($config);
    }

    public function testGetSetRenderer()
    {
        $responder = new HtmlResponder($this->config);
        $responder->setRenderer(new PhtmlRenderer($this->config));
        $this->assertInstanceOf(PhtmlRenderer::class, $responder->getRenderer());
    }

    public function testAssign()
    {
        $responder = new HtmlResponder($this->config);
        $responder->assign(['mock' => 'foobar']);
        $renderer = $responder->getRenderer();
        $output = $renderer->render('simple_view');
        $this->assertEquals('foobar', $output);
    }
}
