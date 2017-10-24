<?php

namespace Nekudo\ShinyCoreApp\Domains;

use Nekudo\ShinyCore\Domain;

class HomeDomain extends Domain
{
    public function getSomeData()
    {
        return 'Welcome Home...';
    }
}
