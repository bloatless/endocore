<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer\PreCompiler;

use Bloatless\Endocore\Components\PhtmlRenderer\TemplatingException;

/**
 * The LayoutPreCompiler handles extends, and includes within a view.
 *
 * @package Bloatless\Endocore\Components\PhtmlRenderer\PreCompiler
 */
class LayoutPreCompiler implements PreCompilerInterface
{
    /**
     * Path to directory containing the view files.
     *
     * @var string $viewPath
     */
    private string $viewPath;

    /**
     * Compiles "includes", and "extends" within a view.
     *
     * @param string $viewContent
     * @param array $templateVariables
     * @return string
     * @throws TemplatingException
     */
    public function compile(string $viewContent, array $templateVariables = []): string
    {
        $viewContent = $this->compileExtends($viewContent);
        $viewContent = $this->compileIncludes($viewContent);

        return $viewContent;
    }

    /**
     * Puts content of a view into the "layout" which is extended.
     *
     * @param string $viewContent
     * @return string
     * @throws TemplatingException
     */
    private function compileExtends(string $viewContent): string
    {
        $matchCount = preg_match('/\{%\sextends\(\'(.+)\'\)\s%\}/Usi', $viewContent, $matches);
        if ($matchCount !== 1) {
            return $viewContent;
        }

        $layoutName = $matches[1];
        $pathToLayout = sprintf('%s/%s.phtml', $this->viewPath, $layoutName);
        if (!file_exists($pathToLayout)) {
            throw new TemplatingException(sprintf('Layout file not found on disk (%s)', $pathToLayout));
        }
        $layoutContent = file_get_contents($pathToLayout);
        if (strpos($layoutContent, '{% $view %}') === false) {
            throw new TemplatingException(
                sprintf('Invalid Layout file. View placeholder missing. (%s)', $pathToLayout)
            );
        }
        $viewContent = str_replace($matches[0], '', $viewContent);
        $viewContent = str_replace('{% $view %}', $viewContent, $layoutContent);


        return $viewContent;
    }

    /**
     * Puts content from an include into the view.
     *
     * @param string $viewContent
     * @return string
     * @throws TemplatingException
     */
    private function compileIncludes(string $viewContent): string
    {
        $includePattern = '/\{%\sinclude\(\'(.+)\'\)\s%\}/Us';
        $matchCount = preg_match_all($includePattern, $viewContent, $matches, PREG_SET_ORDER);
        if ($matchCount === 0) {
            return $viewContent;
        }

        foreach ($matches as $match) {
            $tag = $match[0];
            $pathToView = sprintf('%s/%s.phtml', $this->viewPath, $match[1]);
            if (!file_exists($pathToView)) {
                throw new TemplatingException(sprintf('Include file not found on disk (%s)', $pathToView));
            }
            $includeContent = file_get_contents($pathToView);
            if (preg_match($includePattern, $includeContent) > 0) {
                $includeContent = $this->compileIncludes($includeContent);
            }
            $viewContent = str_replace($tag, $includeContent, $viewContent);
        }

        return $viewContent;
    }

    /**
     * @param string $viewPath
     * @throws TemplatingException
     * @return void
     */
    public function setViewPath(string $viewPath): void
    {
        if (!file_exists($viewPath)) {
            throw new TemplatingException(sprintf('View path not found on disk (%s).', $viewPath));
        }
        $this->viewPath = rtrim($viewPath, '/');
    }
}
