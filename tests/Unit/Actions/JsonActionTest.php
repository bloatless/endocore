<?php

namespace Nekudo\ShinyCore\Tests\Unit\Action;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Logger\NullLogger;
use Nekudo\ShinyCore\Responder\JsonResponder;
use Nekudo\ShinyCore\Http\Request;
use Nekudo\ShinyCore\Tests\Fixtures\HelloWorldJsonAction;
use PHPUnit\Framework\TestCase;

class JsonActionTest extends TestCase
{
    public $config;

    public $logger;

    public function setUp()
    {
        $config = include SC_TESTS . '/Fixtures/config.php';
        $this->config = (new Config)->fromArray($config);
        $this->logger = new NullLogger;
    }

    public function testGetResponder()
    {
        $request = new Request;
        $action = new HelloWorldJsonAction($this->config, $this->logger, $request);
        $this->assertInstanceOf(JsonResponder::class, $action->getResponder());
    }
}
