<?php

namespace Nekudo\ShinyCoreApp\Actions;

use Nekudo\ShinyCore\Action;

class HomeAction extends Action
{
    public function __invoke(array $arguments = [])
    {
        echo "Hello. Welcome home..."; 
    }
}
