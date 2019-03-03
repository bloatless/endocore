<?php

namespace Bloatless\Endocore\Tests\Fixtures;

use Bloatless\Endocore\Action\HtmlAction;
use Bloatless\Endocore\Http\Response;

class HelloWorldHtmlAction extends HtmlAction
{
    public function __invoke(array $arguments = []): Response
    {
        return new Response(200, [], 'Hello World!');
    }
}
