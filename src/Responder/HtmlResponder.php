<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Responder;

use Bloatless\Endocore\Http\Response;

/**
 * @property string $view
 */

class HtmlResponder extends Responder
{
    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->response->addHeader('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * Renders view defined in data array and passes it to http-responder.
     *
     * @param array $data
     * @return Response
     */
    public function found(array $data): Response
    {
        $this->response->setBody($data['body'] ?? '');
        return $this->response;
    }

    /**
     * Respond with an error message.
     *
     * @return Response
     */
    public function badRequest(): Response
    {
        $this->response->setStatus(400);
        $this->response->setBody('<html><title>400 Bad Request</title>400 Bad Request</html>');
        return $this->response;
    }

    /**
     * Respond with an not found message.
     *
     * @return Response
     */
    public function notFound(): Response
    {
        $this->response->setStatus(404);
        $this->response->setBody('<html><title>404 Not found</title>404 Not found</html>');
        return $this->response;
    }

    /**
     * Respond with an error message.
     *
     * @return Response
     */
    public function methodNotAllowed(): Response
    {
        $this->response->setStatus(405);
        $this->response->setBody('<html><title>405 Method not allowed</title>405 Method not allowed</html>');
        return $this->response;
    }

    /**
     * Respond with an error message.
     *
     * @param array $errors
     * @return Response
     */
    public function error(array $errors): Response
    {
        $this->response->setStatus(500);
        $bodyTemplate = '<html><title>Error 500</title><h1>Server Error</h1><pre>%s</pre></html>';
        $this->response->setBody(sprintf($bodyTemplate, print_r($errors, true)));
        return $this->response;
    }
}
