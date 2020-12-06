<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Responder;

use Bloatless\Endocore\Domain\Payload;
use Bloatless\Endocore\Http\Request;
use Bloatless\Endocore\Http\Response;

class JsonResponder extends Responder
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
