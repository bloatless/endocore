<?php

namespace Nekudo\ShinyCore\Tests\Mocks;

use Nekudo\ShinyCore\Action;

class HelloWorldAction extends Action
{
    public function __invoke(array $arguments = [])
    {
        echo "Hello World!";
    }
}
