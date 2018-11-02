<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Responder;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Exceptions\Application\ShinyCoreException;

/**
 * @property string $view
 */

class HtmlResponder extends HttpResponder
{
    /**
     * @var RendererInterface $renderer
     */
    protected $renderer;

    public function __construct(Config $config)
    {
        parent::__construct($config);
        $this->addHeader('Content-Type', 'text/html; charset=utf-8');
        $this->initRenderer();
    }

    /**
     * Initiates the HTTP renderer defined in config (or default if no renderer is defined).
     *
     * @throws ShinyCoreException
     * @return void
     */
    protected function initRenderer(): void
    {
        $rendererClass = $this->config->getClass('html_renderer', '\Nekudo\ShinyCore\Responder\PhtmlRenderer');
        if (!class_exists($rendererClass)) {
            throw new ShinyCoreException('Renderer class not found.');
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
     * @return void
     */
    public function show(string $view, array $templateVars = []): void
    {
        $this->found([
            'view' => $view,
            'vars' => $templateVars
        ]);
    }

    /**
     * Renders view defined in data array and passes it to http-responder.
     *
     * @param array $data
     * @return void
     */
    public function found(array $data): void
    {
        $view = $data['view'] ?? '';
        $templateVars = $data['vars'] ?? [];
        $this->setBody(
            $this->renderer->render($view, $templateVars)
        );
    }

    /**
     * Respond with an error message.
     *
     * @return void
     */
    public function badRequest(): void
    {
        $this->setStatus(400);
        $this->setBody('<html><title>400 Bad Request</title>400 Bad Request</html>');
    }

    /**
     * Respond with an not found message.
     *
     * @return void
     */
    public function notFound(): void
    {
        $this->setStatus(404);
        $this->setBody('<html><title>404 Not found</title>404 Not found</html>');
    }

    /**
     * Respond with an error message.
     *
     * @return void
     */
    public function methodNotAllowed(): void
    {
        $this->setStatus(405);
        $this->setBody('<html><title>405 Method not allowed</title>405 Method not allowed</html>');
    }

    /**
     * Respond with an error message.
     *
     * @param array $errors
     * @return void
     */
    public function error(array $errors): void
    {
        $this->setStatus(500);
        $bodyTemplate = '<html><title>Error 500</title><h1>Server Error</h1><pre>%s</pre></html>';
        $this->setBody(sprintf($bodyTemplate, print_r($errors, true)));
    }
}
