<?php

declare(strict_types=1);

namespace Nekudo\ShinyCoreApp\Actions;

use Nekudo\ShinyCore\Action\JsonAction;
use Nekudo\ShinyCore\Http\Response;

class JsonDemoAction extends JsonAction
{
    public function __invoke(array $arguments = []): Response
    {
        return $this->responder->found([
            'foo' => 'Some data...'
        ]);
    }
}
