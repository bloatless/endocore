<?php

namespace Bloatless\Endocore\Tests\Unit\Action;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Logger\NullLogger;
use Bloatless\Endocore\Responder\HtmlResponder;
use Bloatless\Endocore\Http\Request;
use Bloatless\Endocore\Tests\Fixtures\HelloWorldHtmlAction;
use PHPUnit\Framework\TestCase;

class HtmlActionTest extends TestCase
{
    public $config;

    public $logger;

    public function setUp(): void
    {
        $config = include SC_TESTS . '/Fixtures/config.php';
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
