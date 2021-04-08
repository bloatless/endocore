<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer;

use Bloatless\Endocore\Components\PhtmlRenderer\Renderer\RendererInterface as ViewRendererInterface;

/**
 * The view renderers a view after it has been processed by the pre-compiler.
 *
 * @package Bloatless\Endocore\Components\PhtmlRenderer
 */
class ViewRenderer
{
    /**
     * List of renderers which can be called from within the view.
     *
     * @var array $renderers
     */
    private array $renderers;

    /**
     * Data passed into the view.
     *
     * @var array $templateVariables
     */
    private array $templateVariables;

    /**
     * Renders the given (pre-compiled) view.
     *
     * @param string $pathToCompiledView
     * @param array $templateVariables
     * @return string
     */
    public function render(string $pathToCompiledView, array $templateVariables = []): string
    {
        $this->templateVariables = $templateVariables;
        extract($templateVariables);
        ob_start();
        include $pathToCompiledView;

        return ob_get_clean();
    }

    /**
     * Calls a renderer (from the view)
     *
     * @param string $rendererName
     * @param array $arguments
     * @throws TemplatingException
     */
    private function call(string $rendererName, array $arguments = []): void
    {
        if (!isset($this->renderers[$rendererName])) {
            throw new TemplatingException(sprintf('Invalid renderer name (%s)', $rendererName));
        }

        /** @var ViewRendererInterface $renderer */
        $renderer = $this->renderers[$rendererName];
        echo $renderer->render($arguments, $this->templateVariables);
    }

    /**
     * @param string $rendererName
     * @param ViewRendererInterface $renderer
     * @return void
     */
    public function addRenderer(string $rendererName, ViewRendererInterface $renderer): void
    {
        $this->renderers[$rendererName] = $renderer;
    }
}
