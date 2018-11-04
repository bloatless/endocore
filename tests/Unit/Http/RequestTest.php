<?php

namespace Nekudo\ShinyCore\Tests\Unit\Http;

use Nekudo\ShinyCore\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @var Request $request
     */
    protected $request;

    public function setUp()
    {
        $this->request = new Request([], [], [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/foo',
            'CONTENT_TYPE' => 'text/plain'
        ]);
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
}
