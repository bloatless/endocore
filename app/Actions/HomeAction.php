<?php

namespace Nekudo\ShinyCoreApp\Actions;

use Nekudo\ShinyCore\Action;
use Nekudo\ShinyCoreApp\Responder\HomeResponder;

/**
 * @property \Nekudo\ShinyCoreApp\Domains\HomeDomain $domain
 */

class HomeAction extends Action
{
    public function __invoke(array $arguments = [])
    {
        $data = $this->domain->getSomeData();
        $responder = new HomeResponder;
        $responder->setBody($data);
        $responder->found();
    }
}
