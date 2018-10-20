<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Exceptions\Application\ClassNotFoundException;
use Nekudo\ShinyCore\Interfaces\RendererInterface;

/**
 * @property string $view
 */

class HtmlResponder extends HttpResponder
{
    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var RendererInterface $renderer
     */
    protected $renderer;

    public function __construct(Config $config, int $statusCode = 200, string $version = '1.1')
    {
        parent::__construct($statusCode, $version);

        $this->config = $config;
        $this->addHeader('Content-Type', 'text/html; charset=utf-8');
        $this->initRenderer();
    }

    protected function initRenderer()
    {
        $rendererClass = $this->config->getClass('renderer', '\Nekudo\ShinyCore\PhtmlRenderer');
        if (!class_exists($rendererClass)) {
            throw new ClassNotFoundException('Renderer class not found.');
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

    public function found(string $view, array $templateVariables = [])
    {
        $this->setBody(
            $this->renderer->render($view, $templateVariables)
        );
    }

    public function notFound()
    {
        $this->setStatus(404);
        $this->setBody('<html><head><title>404 Not found</title></head><body>404 Not found</body></html>');
    }

    public function error()
    {
        $this->setStatus(500);
    }
}
