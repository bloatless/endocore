<?php

namespace Nekudo\ShinyCore\Tests\Mocks;

use Nekudo\ShinyCore\HtmlAction;

class HelloWorldAction extends HtmlAction
{
    public function __invoke(array $arguments = [])
    {
        echo "Hello World!";
    }
}
