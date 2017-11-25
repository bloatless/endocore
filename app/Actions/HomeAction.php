<?php

namespace Nekudo\ShinyCoreApp\Actions;

use Nekudo\ShinyCore\Action;

/**
 * @property \Nekudo\ShinyCoreApp\Domains\HomeDomain $domain
 * @property \Nekudo\ShinyCoreApp\Responder\HomeResponder $responder
 */

class HomeAction extends Action
{
    public function __invoke(array $arguments = [])
    {
        $data = $this->domain->getSomeData();
        $this->responder->found('home', $data);
    }
}
