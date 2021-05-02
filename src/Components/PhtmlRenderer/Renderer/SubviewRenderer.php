<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer\Renderer;

use Bloatless\Endocore\Components\PhtmlRenderer\PhtmlRendererFactory;

/**
 * Renders subviews within a view.
 *
 * @package Bloatless\Endocore\Components\PhtmlRenderer\Renderer
 */
class SubviewRenderer implements RendererInterface
{
    /**
     * @var PhtmlRendererFactory $phtmlRendererFactory
     */
    private PhtmlRendererFactory $phtmlRendererFactory;

    /**
     * @param PhtmlRendererFactory $phtmlRendererFactory
     */
    public function __construct(PhtmlRendererFactory $phtmlRendererFactory)
    {
        $this->phtmlRendererFactory = $phtmlRendererFactory;
    }

    /**
     * Creates a new phtml-renderer instance and renders a subview.
     *
     * @param array $arguments
     * @param array $templateVariables
     * @return string
     * @throws \Bloatless\Endocore\Components\PhtmlRenderer\TemplatingException
     */
    public function render(array $arguments, array $templateVariables): string
    {
        // includes have their own scope so we override template variables
        $templateVariables = $arguments['subviewArguments'] ?? [];
        $phtmlRenderer = $this->phtmlRendererFactory->make();
        $viewName = $arguments['viewName'];

        return $phtmlRenderer->render($viewName, $templateVariables);
    }
}
