<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Responder;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Exception\Application\EndocoreException;
use Bloatless\Endocore\Http\Response;

/**
 * @property string $view
 */

class HtmlResponder extends Responder
{
    /**
     * @var RendererInterface $renderer
     */
    protected $renderer;

    public function __construct(Config $config)
    {
        parent::__construct($config);
        $this->response->addHeader('Content-Type', 'text/html; charset=utf-8');
        $this->initRenderer();
    }

    /**
     * Initiates the HTTP renderer defined in config (or default if no renderer is defined).
     *
     * @throws EndocoreException
     * @return void
     */
    protected function initRenderer(): void
    {
        $rendererClass = $this->config->getClass('html_renderer', '\Bloatless\Endocore\Responder\PhtmlRenderer');
        if (!class_exists($rendererClass)) {
            throw new EndocoreException('Renderer class not found.');
        }
        $this->renderer = new $rendererClass($this->config);
    }

    /**
     * Returns the HTML renderer.
     *
     * @return RendererInterface
     */
    public function getRenderer(): RendererInterface
    {
        return $this->renderer;
    }

    /**
     * Sets the HTML renderer.
     *
     * @param RendererInterface $renderer
     * @return void
     */
    public function setRenderer(RendererInterface $renderer): void
    {
        $this->renderer = $renderer;
    }

    /**
     * Assigns a template variable.
     *
     * @param array $pairs
     * @return void
     */
    public function assign(array $pairs): void
    {
        $this->renderer->assign($pairs);
    }

    /**
     * Renders given view and returns HTML code.
     *
     * @param string $view
     * @param array $templateVars
     * @return string
     */
    public function render(string $view, array $templateVars = []): string
    {
        return $this->renderer->render($view, $templateVars);
    }

    /**
     * Renders given view and passes it to http-responder.
     *
     * @param string $view
     * @param array $templateVars
     * @return Response
     */
    public function show(string $view, array $templateVars = []): Response
    {
        return $this->found([
            'view' => $view,
            'vars' => $templateVars
        ]);
    }

    /**
     * Renders view defined in data array and passes it to http-responder.
     *
     * @param array $data
     * @return Response
     */
    public function found(array $data): Response
    {
        $view = $data['view'] ?? '';
        $templateVars = $data['vars'] ?? [];
        $this->response->setBody(
            $this->renderer->render($view, $templateVars)
        );
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
