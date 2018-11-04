<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Responder\HtmlResponder;
use Nekudo\ShinyCore\Http\Request;
use Nekudo\ShinyCore\Tests\Mocks\HelloWorldHtmlAction;
use PHPUnit\Framework\TestCase;

class HtmlActionTest extends TestCase
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
        $action = new HelloWorldHtmlAction($this->config, $request);
        $this->assertInstanceOf(HtmlResponder::class, $action->getResponder());
    }
}
