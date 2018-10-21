<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Responder\HttpResponder;
use PHPUnit\Framework\TestCase;

class HttpResponderTest extends TestCase
{

    public function testStatus()
    {
        $responder = new HttpResponder;
        $responder->setStatus(418);
        $this->assertEquals(418, $responder->getStatus());
    }

    public function testVersion()
    {
        $responder = new HttpResponder;
        $responder->setVersion('1.0');
        $this->assertEquals('1.0', $responder->getVersion());
    }

    public function testBody()
    {
        $body = 'some html foo';
        $responder = new HttpResponder;
        $responder->setBody($body);
        $this->assertEquals($body, $responder->getBody());
    }

    public function testHeaders()
    {
        $responder = new HttpResponder;
        $responder->addHeader('foo', 'bar');
        $this->assertSame(['foo' => 'bar'], $responder->getHeaders());
        $responder->addHeader('some', 'more');
        $responder->removeHeader('foo');
        $this->assertSame(['some' => 'more'], $responder->getHeaders());
        $responder->clearHeaders();
        $this->assertSame([], $responder->getHeaders());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testRespond()
    {
        $responder = new HttpResponder;

        // test http-status code only
        $this->assertEquals(200, $responder->getStatus());
        $this->expectOutputString('');
        $responder->respond();

        // test additional headers
        $responder->addHeader('foo', 'bar');
        $headers = $responder->getHeaders();
        $this->assertArrayHasKey('foo', $headers);

        $this->expectOutputString('');
        $responder->respond();

        // test with body
        $this->expectOutputString('shiny');
        $responder->setBody('shiny');
        $responder->respond();
    }
}
