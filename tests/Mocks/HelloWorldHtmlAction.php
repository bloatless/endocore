<?php

namespace Nekudo\ShinyCore\Tests\Mocks;

use Nekudo\ShinyCore\Action\HtmlAction;
use Nekudo\ShinyCore\Http\Response;

class HelloWorldHtmlAction extends HtmlAction
{
    public function __invoke(array $arguments = []): Response
    {
        return new Response(200, [], 'Hello World!');
    }
}
