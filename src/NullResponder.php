<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Interfaces\ResponderInterface;

class NullResponder implements ResponderInterface
{
    public function respond()
    {
        return null;
    }
}
