<?php

declare(strict_types=1);

namespace Nekudo\ShinyCoreApp\Actions;

use Nekudo\ShinyCore\JsonAction;

class JsonDemoAction extends JsonAction
{
    public function __invoke(array $arguments = [])
    {
        $this->responder->success(['foo' => 'Some data...']);
    }
}
