<?php

namespace Bloatless\Endocore\Tests\Unit\Responder;

use Bloatless\Endocore\Core\Http\Request;
use Bloatless\Endocore\Core\Http\Response;
use Bloatless\Endocore\Domain\Payload;
use Bloatless\Endocore\Responder\JsonResponder;
use PHPUnit\Framework\TestCase;

class JsonResponderTest extends TestCase
{
    public $config;

    public function setUp(): void
    {
        $this->config = include TESTS_ROOT . '/Fixtures/config/config.php';
    }

    public function testGetSetResponder()
    {
        $responder = new JsonResponder();
        $responder->setResponse(new Response());
        $this->assertInstanceOf(Response::class, $responder->getResponse());
    }

    public function testInvoke()
    {
        $responder = new JsonResponder();
        $request = new Request();
        $payload = new Payload(Payload::STATUS_OK, ['foo' => 'bar']);
        $response = $responder->__invoke($request, $payload);
        $this->assertStringContainsString('"foo":"bar"', $response->getBody());
    }
}
