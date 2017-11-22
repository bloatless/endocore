<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Request;
use Nekudo\ShinyCore\Tests\Mocks\HelloWordResponder;
use Nekudo\ShinyCore\Tests\Mocks\HelloWorldAction;
use Nekudo\ShinyCore\Tests\Mocks\HelloWorldDomain;
use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{
    public $config;

    public function setUp()
    {
        $config = include __DIR__ . '/../Mocks/config.php';
        $this->config = (new Config)->fromArray($config);
    }

    public function testGetSetDomain()
    {
        $request = new Request;
        $action = new HelloWorldAction($this->config, $request);
        $domain = new HelloWorldDomain;
        $action->setDomain($domain);
        $this->assertInstanceOf(HelloWorldDomain::class, $action->getDomain());
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
