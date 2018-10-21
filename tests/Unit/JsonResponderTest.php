<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\JsonResponder;
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
        $responder = new JsonResponder;
        $responder->success(['foo' => 'bar']);
        $this->assertEquals('{"data":{"foo":"bar"}}', $responder->getBody());
    }

    public function testError()
    {
        $responder = new JsonResponder;
        $responder->error(['foo' => 'bar']);
        $this->assertEquals('{"errors":{"foo":"bar"}}', $responder->getBody());
    }

    public function testBadRequest()
    {
        $responder = new JsonResponder;
        $responder->badRequest(['foo' => 'bar']);
        $this->assertEquals('{"errors":{"foo":"bar"}}', $responder->getBody());
    }
}
