<?php

namespace Bloatless\Endocore\Tests\Unit\Http;

use Bloatless\Endocore\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /** @var Response $response */
    protected $response;

    public function setUp(): void
    {
        $this->response = new Response();
    }

    public function testCanBeInitialized()
    {
        $this->assertInstanceOf(Response::class, $this->response);
    }

    public function testGetSetProtocolVersion()
    {
        $this->response->setProtocolVersion('1.0');
        $this->assertEquals('1.0', $this->response->getProtocolVersion());
    }

    public function testGetSetStatus()
    {
        $this->response->setStatus(418);
        $this->assertEquals(418, $this->response->getStatus());
        $this->expectException(\InvalidArgumentException::class);
        $this->response->setStatus(42);
    }

    public function testGetStatusMessage()
    {
        $this->response->setStatus(304);
        $this->assertEquals('Not Modified', $this->response->getStatusMessage());
    }

    public function testGetSetHeaders()
    {
        $this->response->setHeaders(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $this->response->getHeaders());
    }

    public function testAddHeader()
    {
        $this->response->addHeader('some', 'thing');
        $this->assertArrayHasKey('some', $this->response->getHeaders());
    }

    public function testRemoveHeader()
    {
        $this->response->addHeader('foo', 'bar');
        $this->response->removeHeader('foo');
        $this->assertArrayNotHasKey('foo', $this->response->getHeaders());
    }

    public function testClearHeaders()
    {
        $this->response->addHeader('foo', 'bar');
        $this->response->clearHeaders();
        $this->assertEquals([], $this->response->getHeaders());
    }

    public function testGetSetBody()
    {
        $this->response->setBody('foobar');
        $this->assertEquals('foobar', $this->response->getBody());
    }

    public function testToString()
    {
        $response = new Response(200, ['foo' => 'bar'], 'test');
        $responseAsSting = (string) $response;
        $expectedResponse = "HTTP/1.1 200 OK\r\nfoo: bar\r\ntest";
        $this->assertEquals($expectedResponse, $responseAsSting);
    }
}
