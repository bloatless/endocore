<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Responder;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Http\Response;

abstract class Responder implements ResponderInterface
{
    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var Response $response
     */
    protected $response;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->setResponse(new Response);
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
