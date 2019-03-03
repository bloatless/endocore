<?php

namespace Bloatless\Endocore\Tests\Fixtures;

use Bloatless\Endocore\Action\JsonAction;
use Bloatless\Endocore\Http\Response;

class HelloWorldJsonAction extends JsonAction
{
    public function __invoke(array $arguments = []): Response
    {
        return new Response(200, [], 'Hello World!');
    }
}
