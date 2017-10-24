<?php

namespace Nekudo\ShinyCoreApp\Actions;

use Nekudo\ShinyCore\Action;

/**
 * @property \Nekudo\ShinyCoreApp\Domains\HomeDomain $domain
 */

class HomeAction extends Action
{
    public function __invoke(array $arguments = [])
    {
        echo $this->domain->getSomeData();
    }
}
