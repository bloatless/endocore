<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer;

use Bloatless\Endocore\Components\PhtmlRenderer\PreCompiler\PreCompilerInterface;

/**
 * Main class of the phtml renderer package. Compiling and rendering of phtml files is managed from within this class.
 *
 * @package Bloatless\Endocore\Components\PhtmlRenderer
 */
class PhtmlRenderer
{
    /**
     * Path to directory containing the view/phtml files.
     *
     * @var string $viewPath
     */
    private string $viewPath;

    /**
     * Path to directory where compiled views are stored/cached.
     *
     * @var string $compilePath
     */
    private string $compilePath;

    /**
     * List of pre-compiler objects to be executed during view compilation.
     *
     * @var array $preCompilers
     */
    private array $preCompilers;

    /**
     * @var ViewRenderer $viewRenderer
     */
    private ViewRenderer $viewRenderer;

    /**
     * @var array $templateVariables
     */
    protected array $templateVariables = [];

    /**
     * @param ViewRenderer $viewRender
     */
    public function __construct(ViewRenderer $viewRender)
    {
        $this->viewRenderer = $viewRender;
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

    /**
     * @param string $compilePath
     * @throws TemplatingException
     * @return void
     */
    public function setCompilePath(string $compilePath): void
    {
        if (!file_exists($compilePath) || !is_writeable($compilePath)) {
            throw new TemplatingException(
                sprintf('Compile path not found on disk or not writeable (%s).', $compilePath)
            );
        }
        $this->compilePath = rtrim($compilePath, '/');
    }

    /**
     * @param PreCompilerInterface $preCompiler
     * @return void
     */
    public function addPreCompiler(PreCompilerInterface $preCompiler): void
    {
        $this->preCompilers[] = $preCompiler;
    }

    /**
     * Renders the given view. The view file is first compiled and saved to the compile cache. The resulting
     * php file is then rendered. The output is buffered and returned as a string of html.
     *
     * @param string $viewName
     * @param array $templateVariables
     * @return string
     * @throws TemplatingException
     */
    public function render(string $viewName = '', array $templateVariables = []): string
    {
        $viewContent = $this->getViewContent($viewName);
        $viewContent = $this->preCompile($viewContent, $templateVariables);
        $pathToCompiledView = $this->storeCompiledView($viewName, $viewContent);

        return $this->renderCompiledView($pathToCompiledView, $templateVariables);
    }

    /**
     * Loads the contents of a given view from disk.
     *
     * @param string $viewName
     * @return string
     * @throws TemplatingException
     */
    private function getViewContent(string $viewName): string
    {
        $pathToView = sprintf('%s/%s.phtml', $this->viewPath, $viewName);
        if (!file_exists($pathToView)) {
            throw new TemplatingException(sprintf('View file not found on disk (%s)', $pathToView));
        }

        return file_get_contents($pathToView);
    }

    /**
     * Executes all pre-compiles (if any) on the given view.
     *
     * @param string $viewContent
     * @param array $templateVariables
     * @return string
     */
    private function preCompile(string $viewContent, array $templateVariables): string
    {
        if (empty($this->preCompilers)) {
            return $viewContent;
        }

        /** @var PreCompilerInterface $preCompiler */
        foreach ($this->preCompilers as $preCompiler) {
            $viewContent = $preCompiler->compile($viewContent, $templateVariables);
        }

        return $viewContent;
    }

    /**
     * Saves the result of a compiled view to disk.
     *
     * @param string $viewName
     * @param string $viewContent
     * @return string
     * @throws TemplatingException
     */
    private function storeCompiledView(string $viewName, string $viewContent): string
    {
        $viewHash = $this->getViewHash($viewName);
        $pathToCompiledView = sprintf('%s/%s.php', $this->compilePath, $viewHash);
        $written = file_put_contents($pathToCompiledView, $viewContent);
        if ($written === false) {
            throw new TemplatingException('Could not store compiled view.');
        }

        return $pathToCompiledView;
    }

    /**
     * Renders a previously compiled view. Basically this means including the php file and buffering the output.
     *
     * @param string $pathToCompiledView
     * @param array $templateVariables
     * @return string
     */
    private function renderCompiledView(string $pathToCompiledView, array $templateVariables): string
    {
        return $this->viewRenderer->render($pathToCompiledView, $templateVariables);
    }

    /**
     * Generates and returns a unique hash for a given view. The hash is an md5 of the full path to the view file.
     *
     * @param string $viewName
     * @return string
     */
    private function getViewHash(string $viewName): string
    {
        $pathToView = sprintf('%s/%s.phtml', $this->viewPath, $viewName);

        return md5($pathToView);
    }

    /**
     * Assigns template variables.
     *
     * @deprecated we should remove this...
     *
     * @param array $pairs
     * @return void
     */
    public function assign(array $pairs): void
    {
        $this->templateVariables = array_merge($this->templateVariables, $pairs);
    }
}
