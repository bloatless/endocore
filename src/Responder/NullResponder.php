<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Responder;

class NullResponder implements ResponderInterface
{
    public function respond()
    {
        return null;
    }
}
