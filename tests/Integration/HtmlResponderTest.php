<?php

namespace Nekudo\ShinyCore\Tests\Integration;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Responder\HtmlResponder;
use PHPUnit\Framework\TestCase;

class HtmlResponderTest extends TestCase
{
    public $config;

    public function setUp()
    {
        $configData = include __DIR__ . '/../Mocks/config.php';
        $this->config = (new Config)->fromArray($configData);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testFoundRequest()
    {
        $responder = new HtmlResponder($this->config);
        $responder->found('simple_view', ['mock' => 'foobar']);
        $this->assertEquals(200, $responder->getStatus());
        $this->expectOutputString('foobar');
        $responder->respond();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testNotFoundRequest()
    {
        $responder = new HtmlResponder($this->config);
        $responder->notFound();
        $this->assertEquals(404, $responder->getStatus());
        $this->expectOutputString('<html><head><title>404 Not found</title></head><body>404 Not found</body></html>');
        $responder->respond();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testErrorRequest()
    {
        $responder = new HtmlResponder($this->config);
        $responder->error();
        $this->assertEquals(500, $responder->getStatus());
        $this->expectOutputString('');
        $responder->respond();
    }
}
