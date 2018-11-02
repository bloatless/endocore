<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Actions;

use Nekudo\ShinyCore\Responder\ResponderInterface;

interface ActionInterface
{
    /**
     * Executes the action.
     *
     * @param array $arguments
     */
    public function __invoke(array $arguments = []): void;

    /**
     * Returns the responder.
     *
     * @return ResponderInterface
     */
    public function getResponder(): ResponderInterface;
}
