<?php

namespace Nekudo\ShinyCore\Tests\Unit\Action;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Logger\NullLogger;
use Nekudo\ShinyCore\Responder\HtmlResponder;
use Nekudo\ShinyCore\Http\Request;
use Nekudo\ShinyCore\Tests\Mocks\HelloWorldHtmlAction;
use PHPUnit\Framework\TestCase;

class HtmlActionTest extends TestCase
{
    public $config;

    public $logger;

    public function setUp()
    {
        $config = include SC_TESTS . '/Mocks/config.php';
        $this->config = (new Config)->fromArray($config);
        $this->logger = new NullLogger;
    }

    public function testGetResponder()
    {
        $request = new Request;
        $action = new HelloWorldHtmlAction($this->config, $this->logger, $request);
        $this->assertInstanceOf(HtmlResponder::class, $action->getResponder());
    }
}
