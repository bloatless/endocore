<?php

namespace Nekudo\ShinyCoreApp\Actions;

use Nekudo\ShinyCore\Action\HtmlAction;
use Nekudo\ShinyCore\Http\Response;
use Nekudo\ShinyCoreApp\Domains\HomeDomain;

class HomeAction extends HtmlAction
{
    public function __invoke(array $arguments = []): Response
    {
        $domain = new HomeDomain;
        return $this->responder->show('home', $domain->getSomeData());
    }
}
