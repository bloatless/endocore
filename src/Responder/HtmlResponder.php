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

    protected function initRenderer()
    {
        $rendererClass = $this->config->getClass('html_renderer', '\Nekudo\ShinyCore\Responder\PhtmlRenderer');
        if (!class_exists($rendererClass)) {
            throw new ShinyCoreException('Renderer class not found.');
        }
        $this->renderer = new $rendererClass($this->config);
    }

    public function getRenderer(): RendererInterface
    {
        return $this->renderer;
    }

    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function assign(array $pairs): void
    {
        $this->renderer->assign($pairs);
    }

    public function render(string $view, array $templateVars = []): string
    {
        return $this->renderer->render($view, $templateVars);
    }

    public function show(string $view, array $templateVars = []): void
    {
        $this->found([
            'view' => $view,
            'vars' => $templateVars
        ]);
    }

    public function found(array $data): void
    {
        $view = $data['view'] ?? '';
        $templateVars = $data['vars'] ?? [];
        $this->setBody(
            $this->renderer->render($view, $templateVars)
        );
    }

    public function badRequest(): void
    {
        $this->setStatus(400);
        $this->setBody('<html><title>400 Bad Request</title>400 Bad Request</html>');
    }

    public function notFound(): void
    {
        $this->setStatus(404);
        $this->setBody('<html><title>404 Not found</title>404 Not found</html>');
    }

    public function methodNotAllowed(): void
    {
        $this->setStatus(405);
        $this->setBody('<html><title>405 Method not allowed</title>405 Method not allowed</html>');
    }

    public function error(array $errors): void
    {
        $this->setStatus(500);
        $bodyTemplate = '<html><title>Error 500</title><h1>Server Error</h1><pre>%s</pre></html>';
        $this->setBody(sprintf($bodyTemplate, print_r($errors, true)));
    }
}
