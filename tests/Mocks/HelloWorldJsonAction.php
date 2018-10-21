<?php

namespace Nekudo\ShinyCore\Tests\Mocks;

use Nekudo\ShinyCore\JsonAction;

class HelloWorldJsonAction extends JsonAction
{
    public function __invoke(array $arguments = [])
    {
        echo "Hello World!";
    }
}
