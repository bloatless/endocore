<?php

namespace Nekudo\ShinyCore\Tests\Mocks;

use Nekudo\ShinyCore\Actions\HtmlAction;

class HelloWorldHtmlAction extends HtmlAction
{
    public function __invoke(array $arguments = [])
    {
        echo "Hello World!";
    }
}
