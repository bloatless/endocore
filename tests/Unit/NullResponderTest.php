<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Responder\NullResponder;
use PHPUnit\Framework\TestCase;

class NullResponderTest extends TestCase
{
    public function testCanBeInitialized()
    {
        $configData = include __DIR__ . '/../Mocks/config.php';
        $config = (new Config)->fromArray($configData);
        $responder = new NullResponder($config);
        $this->assertInstanceOf(NullResponder::class, $responder);
    }
}
