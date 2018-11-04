<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Actions;

use Nekudo\ShinyCore\Http\Response;

interface ActionInterface
{
    /**
     * Executes the action.
     *
     * @param array $arguments
     * @return Response
     */
    public function __invoke(array $arguments = []): Response;
}
