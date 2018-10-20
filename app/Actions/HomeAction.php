<?php

namespace Nekudo\ShinyCoreApp\Actions;

use Nekudo\ShinyCore\HtmlAction;
use Nekudo\ShinyCoreApp\Domains\HomeDomain;

/**
 * @property \Nekudo\ShinyCoreApp\Domains\HomeDomain $domain
 * @property \Nekudo\ShinyCoreApp\Responder\HomeResponder $responder
 */

class HomeAction extends HtmlAction
{
    public function __invoke(array $arguments = [])
    {
        $domain = new HomeDomain;
        $data = $domain->getSomeData();

        $this->responder->found('home', $data);
    }
}
