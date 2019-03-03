<?php

namespace Bloatless\Endocore\Tests\Unit\Responder;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Exception\Application\EndocoreException;
use Bloatless\Endocore\Http\Response;
use Bloatless\Endocore\Responder\HtmlResponder;
use Bloatless\Endocore\Responder\PhtmlRenderer;
use PHPUnit\Framework\TestCase;

class HtmlResponderTest extends TestCase
{
    public $configData;

    public $config;

    public function setUp()
    {
        $this->configData = include SC_TESTS . '/Fixtures/config.php';
        $this->config = (new Config)->fromArray($this->configData);
    }

    public function testGetSetResponder()
    {
        $responder = new HtmlResponder($this->config);
        $responder->setResponse(new Response);
        $this->assertInstanceOf(Response::class, $responder->getResponse());
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
        $this->expectException(EndocoreException::class);
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
        $response = $responder->show('simple_view', ['mock' => 'foobar']);
        $this->assertEquals('foobar', $response->getBody());
    }

    public function testFound()
    {
        $responder = new HtmlResponder($this->config);
        $response = $responder->found([
            'view' => 'simple_view',
            'vars' => ['mock' => 'bar'],
        ]);
        $this->assertEquals('bar', $response->getBody());
    }

    public function testBadRequest()
    {
        $responder = new HtmlResponder($this->config);
        $response = $responder->badRequest();
        $this->assertEquals(400, $response->getStatus());
    }

    public function testNotFound()
    {
        $responder = new HtmlResponder($this->config);
        $response = $responder->notFound();
        $this->assertEquals(404, $response->getStatus());
    }

    public function testMethodNotAllowed()
    {
        $responder = new HtmlResponder($this->config);
        $response = $responder->methodNotAllowed();
        $this->assertEquals(405, $response->getStatus());
    }

    public function testError()
    {
        $responder = new HtmlResponder($this->config);
        $response = $responder->error(['foo' => 'testing error']);
        $this->assertEquals(500, $response->getStatus());
        $this->assertContains('testing error', $response->getBody());
    }
}
