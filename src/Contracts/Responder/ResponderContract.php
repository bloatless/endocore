<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Contracts\Responder;

use Bloatless\Endocore\Domain\Payload;
use Bloatless\Endocore\Core\Http\Request;
use Bloatless\Endocore\Core\Http\Response;

interface ResponderContract
{
    public function __invoke(Request $request, Payload $payload): Response;
}
