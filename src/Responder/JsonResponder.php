<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Responder;

use Bloatless\Endocore\Contracts\Responder\ResponderContract;
use Bloatless\Endocore\Domain\Payload;
use Bloatless\Endocore\Core\Http\Request;
use Bloatless\Endocore\Core\Http\Response;

class JsonResponder extends Responder implements ResponderContract
{
    public function __construct()
    {
        parent::__construct();
        $this->response->addHeader('Content-Type', 'application/json');
    }

    public function __invoke(Request $request, Payload $payload): Response
    {
        $this->response->setStatus($payload->getStatus());
        $this->response->setBody($payload->asJson());

        return $this->response;
    }
}
