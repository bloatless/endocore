<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Responder\JsonResponder;
use PHPUnit\Framework\TestCase;

class JsonResponderTest extends TestCase
{
    public $config;

    public function setUp()
    {
        $configData = include __DIR__ . '/../Mocks/config.php';
        $this->config = (new Config)->fromArray($configData);
    }

    public function testSuccess()
    {
        $responder = new JsonResponder($this->config);
        $response = $responder->found(['foo' => 'bar']);
        $this->assertEquals('{"data":{"foo":"bar"}}', $response->getBody());
    }

    public function testError()
    {
        $responder = new JsonResponder($this->config);
        $response = $responder->error(['foo' => 'bar']);
        $this->assertEquals(500, $response->getStatus());
        $this->assertEquals('{"errors":{"foo":"bar"}}', $response->getBody());
    }

    public function testBadRequest()
    {
        $responder = new JsonResponder($this->config);
        $response = $responder->badRequest();
        $this->assertEquals('', $response->getBody());
        $this->assertEquals(400, $response->getStatus());
    }

    public function testNotFound()
    {
        $responder = new JsonResponder($this->config);
        $response = $responder->notFound();
        $this->assertEquals('', $response->getBody());
        $this->assertEquals(404, $response->getStatus());
    }

    public function testMethodNotAllowed()
    {
        $responder = new JsonResponder($this->config);
        $response = $responder->methodNotAllowed();
        $this->assertEquals('', $response->getBody());
        $this->assertEquals(405, $response->getStatus());
    }
}
