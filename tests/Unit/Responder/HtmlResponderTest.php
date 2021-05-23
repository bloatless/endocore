<?php

namespace Bloatless\Endocore\Tests\Unit\Responder;

use Bloatless\Endocore\Components\Templating\PhtmlRenderer;
use Bloatless\Endocore\Core\Http\Request;
use Bloatless\Endocore\Core\Http\Response;
use Bloatless\Endocore\Domain\Payload;
use Bloatless\Endocore\Responder\HtmlResponder;
use PHPUnit\Framework\TestCase;

class HtmlResponderTest extends TestCase
{
    public $configData;

    public $config;

    public function setUp(): void
    {
        $this->config = include TESTS_ROOT . '/Fixtures/config/config.php';
    }

    public function testGetSetResponder()
    {
        $responder = new HtmlResponder();
        $responder->setResponse(new Response);
        $this->assertInstanceOf(Response::class, $responder->getResponse());
    }

    public function testInvoke()
    {
        $responder = new HtmlResponder();
        $request = new Request();
        $payload = new Payload(Payload::STATUS_OK, []);
        $response = $responder->__invoke($request, $payload);
        $this->assertInstanceOf(Response::class, $response);

        $payload = new Payload(Payload::STATUS_ERROR, []);
        $response = $responder->__invoke($request, $payload);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testError()
    {
        $responder = new HtmlResponder();
        $payload = new Payload(Payload::STATUS_ERROR, []);
        $response = $responder->error($payload);
        $this->assertStringContainsString('Error', $response->getBody());
    }

    public function testProvideResponse()
    {
        $responder = new HtmlResponder();
        $payload = new Payload(Payload::STATUS_OK, []);
        $response = $responder->provideResponse($payload);
        $this->assertStringContainsString('title', $response->getBody());
    }
}
