<?php

declare(strict_types=1);

namespace Nekudo\ShinyCoreApp\Actions;

use Nekudo\ShinyCore\Actions\JsonAction;

class JsonDemoAction extends JsonAction
{
    public function __invoke(array $arguments = [])
    {
        $this->responder->found([
            'foo' => 'Some data...'
        ]);
    }
}
