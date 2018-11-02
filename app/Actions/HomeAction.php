<?php

namespace Nekudo\ShinyCoreApp\Actions;

use Nekudo\ShinyCore\Actions\HtmlAction;
use Nekudo\ShinyCoreApp\Domains\HomeDomain;

class HomeAction extends HtmlAction
{
    public function __invoke(array $arguments = []): void
    {
        $domain = new HomeDomain;
        $this->responder->show('home', $domain->getSomeData());
    }
}
