<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer;

/**
 * @package Bloatless\Endocore\Components\PhtmlRenderer
 * @deprecated This should be a contract in a separate repository.
 */
interface RendererInterface
{
    /**
     * Assigns template variables.
     *
     * @param array $pairs
     * @return void
     */
    public function assign(array $pairs) : void;

    /**
     * Renders given view and return html-code.
     *
     * @param string $view
     * @param array $data
     * @return string
     */
    public function render(string $view = '', array $data = []) : string;
}
