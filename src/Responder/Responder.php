<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Responder;

use Bloatless\Endocore\Core\Http\Response;

abstract class Responder
{
    /**
     * @var Response $response
     */
    protected Response $response;

    public function __construct()
    {
        $this->setResponse(
            new Response()
        );
    }

    /**
     * Sets response.
     *
     * @param Response $response
     */
    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    /**
     * Returns response object.
     *
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
