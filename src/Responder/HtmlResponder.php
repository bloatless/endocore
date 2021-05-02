<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Responder;

use Bloatless\Endocore\Contracts\Responder\ResponderContract;
use Bloatless\Endocore\Domain\Payload;
use Bloatless\Endocore\Core\Http\Request;
use Bloatless\Endocore\Core\Http\Response;

class HtmlResponder extends Responder implements ResponderContract
{
    public function __construct()
    {
        parent::__construct();
        $this->response->addHeader('Content-Type', 'text/html; charset=utf-8');
    }

    public function __invoke(Request $request, Payload $payload): Response
    {
        switch ($payload->getStatus()) {
            case Payload::STATUS_ERROR:
                return $this->error($payload);
            default:
                return $this->provideResponse($payload);
        }
    }

    /**
     * Respond with an error message.
     *
     * @param Payload $payload
     * @return Response
     */
    public function error(Payload $payload): Response
    {
        $this->response->setStatus($payload->getStatus());
        $bodyTemplate = '<html><title>Error 500</title><h1>Server Error</h1><pre>%s</pre></html>';
        $this->response->setBody(sprintf($bodyTemplate, print_r($payload->asArray(), true)));

        return $this->response;
    }

    /**
     * Provides a generic html response.
     *
     * @param Payload $payload
     * @return Response
     */
    public function provideResponse(Payload $payload): Response
    {
        $this->response->setStatus($payload->getStatus());
        $body = $payload['body'] ?? '';
        if (empty($body)) {
            $content = $payload->getStatus() . ' ' . $this->response->getStatusMessage();
            $body = sprintf('<html><title>%s</title>%s</html>', $content, $content);
        }
        $this->response->setBody($body);

        return $this->response;
    }
}
