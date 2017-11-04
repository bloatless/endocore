<?php

namespace Nekudo\ShinyCore\Tests\Unit;

use Nekudo\ShinyCore\Request;
use Nekudo\ShinyCore\Tests\Mocks\MockAction;
use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{
    public function testInvoke()
    {
        $config = include __DIR__ . '/../Mocks/config.php';
        $request = new Request;
        $action = new MockAction($config, $request);
        $this->assertTrue($action->__invoke());
    }
}
