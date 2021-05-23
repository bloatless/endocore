<?php

namespace Bloatless\Endocore\Tests\Fixtures\Action;

use Bloatless\Endocore\Contracts\Action\ActionContract;
use Bloatless\Endocore\Core\Http\Request;
use Bloatless\Endocore\Core\Http\Response;

class HelloWorldAction implements ActionContract
{
    public function __invoke(Request $request, array $arguments = []): Response
    {
        return new Response(200, [], 'Hello World!');
    }
}
