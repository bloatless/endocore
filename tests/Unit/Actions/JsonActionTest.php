<?php

namespace Bloatless\Endocore\Tests\Unit\Action;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Logger\NullLogger;
use Bloatless\Endocore\Responder\JsonResponder;
use Bloatless\Endocore\Http\Request;
use Bloatless\Endocore\Tests\Fixtures\HelloWorldJsonAction;
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
