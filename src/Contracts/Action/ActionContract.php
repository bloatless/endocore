<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Contracts\Action;

use Bloatless\Endocore\Core\Http\Request;
use Bloatless\Endocore\Core\Http\Response;

interface ActionContract
{
    /**
     * Executes the action.
     *
     * @param Request $request
     * @param array $arguments
     * @return Response
     */
    public function __invoke(Request $request, array $arguments = []): Response;
}
