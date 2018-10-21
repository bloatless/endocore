<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\HttpResponder;
use PHPUnit\Framework\TestCase;

class HttpResponderTest extends TestCase
{

    public function testStatus()
    {
        $reponder = new HttpResponder;
        $reponder->setStatus(418);
        $this->assertEquals(418, $reponder->getStatus());
    }

    public function testVersion()
    {
        $reponder = new HttpResponder;
        $reponder->setVersion('1.0');
        $this->assertEquals('1.0', $reponder->getVersion());
    }

    public function testBody()
    {
        $body = 'some html foo';
        $reponder = new HttpResponder;
        $reponder->setBody($body);
        $this->assertEquals($body, $reponder->getBody());
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
     * @todo Move to integration
     */
    public function testRespond()
    {
        $reponder = new HttpResponder;

        // test http-status code only
        $this->expectOutputString('');
        $reponder->respond();

        // test additional headers
        $reponder->addHeader('foo', 'bar');
        $reponder->respond();

        // test with body
        $this->expectOutputString('shiny');
        $reponder->setBody('shiny');
        $reponder->respond();
    }
}
