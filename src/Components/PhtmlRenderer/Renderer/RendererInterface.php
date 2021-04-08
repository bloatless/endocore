<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer\Renderer;

/**
 * Renderers are called from within a precompiled view and render parts of the view in a
 * separate scope. (e.g. subviews or view components)
 *
 * @package Bloatless\Endocore\Components\PhtmlRenderer\Renderer
 */
interface RendererInterface
{
    public function render(array $arguments, array $templateVariables): string;
}
