<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer\PreCompiler;

/**
 * Pre-compilers can be used to replace shortcodes, html-tags eg. with php-code in a view before it is rendered.
 *
 * @package Bloatless\Endocore\Components\PhtmlRenderer\PreCompiler
 */
interface PreCompilerInterface
{
    public function compile(string $content, array $templateVariables = []): string;
}
