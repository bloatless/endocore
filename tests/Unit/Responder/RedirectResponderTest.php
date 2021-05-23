<?php

namespace Bloatless\Endocore\Tests\Unit\Responder;

use Bloatless\Endocore\Core\Http\Request;
use Bloatless\Endocore\Core\Http\Response;
use Bloatless\Endocore\Domain\Payload;
use Bloatless\Endocore\Responder\RedirectResponder;
use PHPUnit\Framework\TestCase;

class RedirectResponderTest extends TestCase
{
    public $config;

    public function setUp(): void
    {
        $this->config = include TESTS_ROOT . '/Fixtures/config/config.php';
    }

    public function testGetSetResponder()
    {
        $responder = new RedirectResponder();
        $responder->setResponse(new Response());
        $this->assertInstanceOf(Response::class, $responder->getResponse());
    }

    public function testInvoke()
    {
        $responder = new RedirectResponder();
        $request = new Request();
        $payload = new Payload(Payload::STATUS_OK, ['location' => 'http://example.com']);
        $response = $responder->__invoke($request, $payload);
        $this->assertArrayHasKey('Location', $response->getHeaders());
    }
}
