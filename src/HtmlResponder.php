<?php

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Interfaces\RendererInterface;
use Nekudo\ShinyCore\Interfaces\ResponderInterface;

class HtmlResponder extends HttpResponder implements ResponderInterface
{
    protected $config;

    /**
     * @var RendererInterface $renderer
     */
    protected $renderer;

    public function __construct(array $config, int $statusCode = 200, string $version = '1.1')
    {
        $this->config = $config;
        parent::__construct($statusCode, $version);
        $this->addHeader('Content-Type', 'text/html; charset=utf-8');
    }

    public function getRenderer() : RendererInterface
    {
        return $this->renderer;
    }

    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function found()
    {
        $this->respond();
    }

    public function notFound()
    {
        $this->setStatus(404);
        $this->setBody('<html><head><title>404 Not found</title></head><body>404 Not found</body></html>');
        $this->respond();
    }

    public function error()
    {
        $this->setStatus(500);
        $this->respond();
    }
}
