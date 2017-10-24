<?php

namespace Nekudo\ShinyCore\Tests\Mocks;

use Nekudo\ShinyCore\Action;

class MockAction extends Action
{
    public function __invoke(array $arguments = [])
    {
        return true;
    }
}
