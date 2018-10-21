<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Responder\NullResponder;
use PHPUnit\Framework\TestCase;

class NullResponderTest extends TestCase
{
    public function testRespond()
    {
        $responder = new NullResponder;
        $this->assertEquals(null, $responder->respond());
    }
}