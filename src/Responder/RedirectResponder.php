<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Responder;

use Bloatless\Endocore\Domain\Payload;
use Bloatless\Endocore\Components\Http\Request;
use Bloatless\Endocore\Components\Http\Response;

class RedirectResponder extends Responder
{
    public function __invoke(Request $request, Payload $payload): Response
    {
        $location = $payload['location'] ?? '/';
        $this->response->addHeader('Location', $location);
        $this->response->setStatus($payload->getStatus());

        return $this->response;
    }
}
