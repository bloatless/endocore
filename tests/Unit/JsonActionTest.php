<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Responder\JsonResponder;
use Nekudo\ShinyCore\Http\Request;
use Nekudo\ShinyCore\Tests\Mocks\HelloWorldJsonAction;
use PHPUnit\Framework\TestCase;

class JsonActionTest extends TestCase
{
    public $config;

    public function setUp()
    {
        $config = include __DIR__ . '/../Mocks/config.php';
        $this->config = (new Config)->fromArray($config);
    }

    public function testGetResponder()
    {
        $request = new Request;
        $action = new HelloWorldJsonAction($this->config, $request);
        $this->assertInstanceOf(JsonResponder::class, $action->getResponder());
    }
}
