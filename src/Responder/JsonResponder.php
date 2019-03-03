<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Responder;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Http\Response;

class JsonResponder extends Responder
{
    public function __construct(Config $config)
    {
        parent::__construct($config);
        $this->response->addHeader('Content-Type', 'application/json');
    }

    /**
     * Respond with data.
     *
     * @param array $data
     * @return Response
     */
    public function found(array $data): Response
    {
        $this->response->setBody(json_encode(['data' => $data]));
        return $this->response;
    }

    /**
     * Respond with an error code 400.
     *
     * @return Response
     */
    public function badRequest(): Response
    {
        $this->response->setStatus(400);
        return $this->response;
    }

    /**
     * Respond with an error code 404.
     *
     * @return Response
     */
    public function notFound(): Response
    {
        $this->response->setStatus(404);
        return $this->response;
    }

    /**
     * Respond with an error code 405.
     *
     * @return Response
     */
    public function methodNotAllowed(): Response
    {
        $this->response->setStatus(405);
        return $this->response;
    }

    /**
     * Respond with an error (code 500).
     *
     * @param array $errors
     * @return Response
     */
    public function error(array $errors): Response
    {
        $this->response->setStatus(500);
        $this->response->setBody(json_encode(['errors' => $errors]));
        return $this->response;
    }
}
