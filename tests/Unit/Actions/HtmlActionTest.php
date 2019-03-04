<?php

namespace Bloatless\Endocore\Tests\Unit\Action;

use Bloatless\Endocore\Components\Logger\NullLogger;
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
        $this->config = include SC_TESTS . '/Fixtures/config.php';
        $this->logger = new NullLogger;
    }

    public function testGetResponder()
    {
        $request = new Request;
        $action = new HelloWorldHtmlAction($this->config, $this->logger, $request);
        $this->assertInstanceOf(HtmlResponder::class, $action->getResponder());
    }
}
