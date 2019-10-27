<?php

namespace Bloatless\Endocore\Tests\Unit\Http;

use Bloatless\Endocore\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @var Request $request
     */
    protected $request;

    public function setUp(): void
    {
        $this->request = new Request(
            [
                'page' => 'home',
            ],
            [
                'name' => 'Homer'
            ],
            [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/foo',
                'CONTENT_TYPE' => 'text/plain'
            ]
        );
    }

    public function testGetRequestMethod()
    {
        $this->assertEquals('GET', $this->request->getRequestMethod());
    }

    public function testGetRequestUri()
    {
        $this->assertEquals('/foo', $this->request->getRequestUri());
    }

    public function testGetContentType()
    {
        $this->assertEquals('text/plain', $this->request->getContentType());
    }

    public function testGetServerParams()
    {
        $request = new Request([], [], ['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $request->getServerParams());
    }

    public function testGetServerParam()
    {
        $this->assertEquals('/foo', $this->request->getServerParam('REQUEST_URI'));
        $this->assertEquals(null, $this->request->getServerParam('foo'));
        $this->assertEquals('bar', $this->request->getServerParam('foo', 'bar'));
    }

    public function testGetParam()
    {
        $this->assertEquals('home', $this->request->getParam('page'));
        $this->assertEquals('Homer', $this->request->getParam('name'));
        $this->assertEquals(null, $this->request->getParam('not_existing'));
        $this->assertEquals('test', $this->request->getParam('not_existing', 'test'));
    }

    public function testGetRawBody()
    {
        $this->assertEquals('', $this->request->getRawBody());
    }
}
