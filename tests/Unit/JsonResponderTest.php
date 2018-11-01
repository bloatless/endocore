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
        $responder->found(['foo' => 'bar']);
        $this->assertEquals('{"data":{"foo":"bar"}}', $responder->getBody());
    }

    public function testError()
    {
        $responder = new JsonResponder($this->config);
        $responder->error(['foo' => 'bar']);
        $this->assertEquals(500, $responder->getStatus());
        $this->assertEquals('{"errors":{"foo":"bar"}}', $responder->getBody());
    }

    public function testBadRequest()
    {
        $responder = new JsonResponder($this->config);
        $responder->badRequest();
        $this->assertEquals('', $responder->getBody());
        $this->assertEquals(400, $responder->getStatus());
    }
}
