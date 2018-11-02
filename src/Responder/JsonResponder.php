<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Responder;

use Nekudo\ShinyCore\Config;

class JsonResponder extends HttpResponder
{
    public function __construct(Config $config)
    {
        parent::__construct($config);
        $this->addHeader('Content-Type', 'application/json');
    }

    /**
     * Respond with data.
     *
     * @param array $data
     * @return void
     */
    public function found(array $data): void
    {
        $this->setBody(json_encode(['data' => $data]));
    }

    /**
     * Respond with an error code 400.
     *
     * @return void
     */
    public function badRequest(): void
    {
        $this->setStatus(400);
    }

    /**
     * Respond with an error code 404.
     *
     * @return void
     */
    public function notFound(): void
    {
        $this->setStatus(404);
    }

    /**
     * Respond with an error code 405.
     *
     * @return void
     */
    public function methodNotAllowed(): void
    {
        $this->setStatus(405);
    }

    /**
     * Respond with an error (code 500).
     *
     * @param array $errors
     * @return void
     */
    public function error(array $errors): void
    {
        $this->setStatus(500);
        $this->setBody(json_encode(['errors' => $errors]));
    }
}
