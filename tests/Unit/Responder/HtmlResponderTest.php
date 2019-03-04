<?php

namespace Bloatless\Endocore\Tests\Unit\Responder;

use Bloatless\Endocore\Components\Templating\PhtmlRenderer;
use Bloatless\Endocore\Http\Response;
use Bloatless\Endocore\Responder\HtmlResponder;
use PHPUnit\Framework\TestCase;

class HtmlResponderTest extends TestCase
{
    public $configData;

    public $config;

    public function setUp(): void
    {
        $this->config = include SC_TESTS . '/Fixtures/config.php';
    }

    public function testGetSetResponder()
    {
        $responder = new HtmlResponder($this->config);
        $responder->setResponse(new Response);
        $this->assertInstanceOf(Response::class, $responder->getResponse());
    }

    public function testGetSetRenderer()
    {
        $responder = new HtmlResponder($this->config);
        $responder->setRenderer(new PhtmlRenderer($this->config['templating']));
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
        $this->assertStringContainsString('testing error', $response->getBody());
    }
}
