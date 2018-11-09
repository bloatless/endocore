<?php

namespace Nekudo\ShinyCore\Tests\Mocks;

use Nekudo\ShinyCore\Action\JsonAction;
use Nekudo\ShinyCore\Http\Response;

class HelloWorldJsonAction extends JsonAction
{
    public function __invoke(array $arguments = []): Response
    {
        return new Response(200, [], 'Hello World!');
    }
}
