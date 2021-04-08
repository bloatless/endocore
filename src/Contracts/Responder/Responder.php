<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Contracts\Responder;

use Bloatless\Endocore\Domain\Payload;
use Bloatless\Endocore\Components\Http\Request;
use Bloatless\Endocore\Components\Http\Response;

interface Responder
{
    public function __invoke(Request $request, Payload $payload): Response;
}
