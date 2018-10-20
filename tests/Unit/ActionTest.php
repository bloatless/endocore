<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Request;
use Nekudo\ShinyCore\Tests\Mocks\HelloWordResponder;
use Nekudo\ShinyCore\Tests\Mocks\HelloWorldAction;
use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{
    public $config;

    public function setUp()
    {
        $config = include __DIR__ . '/../Mocks/config.php';
        $this->config = (new Config)->fromArray($config);
    }

    public function testGetSetResponder()
    {
        $request = new Request;
        $action = new HelloWorldAction($this->config, $request);
        $responder = new HelloWordResponder($this->config);
        $action->setResponder($responder);
        $this->assertInstanceOf(HelloWordResponder::class, $action->getResponder());
    }
}
