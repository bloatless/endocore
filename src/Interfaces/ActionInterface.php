<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Interfaces;

interface ActionInterface
{
    public function __invoke(array $arguments = []);

    public function getResponder(): ResponderInterface;
}
