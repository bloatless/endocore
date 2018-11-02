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

    public function testRender()
    {
        $responder = new HtmlResponder($this->config);
        $this->assertEquals('foobar', $responder->render('simple_view', ['mock' => 'foobar']));
    }

    public function testShow()
    {
        $responder = new HtmlResponder($this->config);
        $responder->show('simple_view', ['mock' => 'foobar']);
        $this->assertEquals('foobar', $responder->getBody());
    }

    public function testFound()
    {
        $responder = new HtmlResponder($this->config);
        $responder->found([
            'view' => 'simple_view',
            'vars' => ['mock' => 'bar'],
        ]);
        $this->assertEquals('bar', $responder->getBody());
    }

    public function testBadRequest()
    {
        $responder = new HtmlResponder($this->config);
        $responder->badRequest();
        $this->assertEquals(400, $responder->getStatus());
    }

    public function testNotFound()
    {
        $responder = new HtmlResponder($this->config);
        $responder->notFound();
        $this->assertEquals(404, $responder->getStatus());
    }

    public function testMethodNotAllowed()
    {
        $responder = new HtmlResponder($this->config);
        $responder->methodNotAllowed();
        $this->assertEquals(405, $responder->getStatus());
    }

    public function testError()
    {
        $responder = new HtmlResponder($this->config);
        $responder->error(['foo' => 'testing error']);
        $this->assertEquals(500, $responder->getStatus());
        $this->assertContains('testing error', $responder->getBody());
    }
}
