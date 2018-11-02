<?php

namespace Nekudo\ShinyCore\Tests\Mocks;

use Nekudo\ShinyCore\Actions\JsonAction;

class HelloWorldJsonAction extends JsonAction
{
    public function __invoke(array $arguments = []): void
    {
        echo "Hello World!";
    }
}
