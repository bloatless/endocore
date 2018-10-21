<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Actions;

use Nekudo\ShinyCore\Responder\ResponderInterface;

interface ActionInterface
{
    public function __invoke(array $arguments = []);

    public function getResponder(): ResponderInterface;
}
