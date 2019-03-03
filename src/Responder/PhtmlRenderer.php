<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Responder;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Exception\Application\EndocoreException;

class PhtmlRenderer implements RendererInterface
{
    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var string $layout
     */
    protected $layout = '';

    /**
     * @var string $view
     */
    protected $view = '';

    /**
     * @var array $templateVariables
     */
    protected $templateVariables = [];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Returns layout name/file.
     *
     * @return string
     */
    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * Sets layout name/file.
     *
     * @param string $layout
     * @return void
     */
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    /**
     * Returns view name.
     *
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * Sets view name.
     *
     * @param string $view
     * @return void
     */
    public function setView(string $view): void
    {
        $this->view = $view;
    }

    /**
     * Assigns template variables.
     *
     * @param array $pairs
     * @return void
     */
    public function assign(array $pairs): void
    {
        $this->templateVariables = array_merge($this->templateVariables, $pairs);
    }

    /**
     * Renders given view and returns html code.

     * @param string $view
     * @param array $variables
     * @throws EndocoreException
     * @return string
     */
    public function render(string $view = '', array $variables = []): string
    {
        if (!empty($view)) {
            $this->setView($view);
        }
        $this->assign($variables);
        $content = $this->renderView();
        if (empty($this->layout)) {
            return $content;
        }
        return $this->renderLayout($content);
    }

    /**
     * Renders a view file.
     *
     * @throws EndocoreException
     * @return string
     */
    protected function renderView(): string
    {
        $viewFile = $this->config->getPath('views') . '/' . $this->view . '.phtml';
        if (!file_exists($viewFile)) {
            throw new EndocoreException(sprintf('View file not found. (%s)', $viewFile));
        }
        $viewContent = file_get_contents($viewFile);
        if (preg_match('/<!-- extends "(.+)" -->/Usi', $viewContent, $matches) === 1) {
            $this->setLayout($matches[1]);
        }

        return $this->renderFile($viewFile);
    }

    /**
     * Renders a layout file.
     *
     * @param string $content
     * @throws EndocoreException
     * @return string
     */
    protected function renderLayout(string $content): string
    {
        $content = str_replace('<!-- extends "' . $this->layout . '" -->', '', $content);
        $content = trim($content);
        $this->assign(['content' => $content]);
        $layoutFile = $this->config->getPath('layouts') . '/' . $this->layout . '.phtml';
        return $this->renderFile($layoutFile);
    }

    /**
     * @param string $templateFile
     * @return string
     * @throws EndocoreException
     */
    protected function renderFile(string $templateFile): string
    {
        if (!file_exists($templateFile)) {
            throw new EndocoreException(sprintf('Template file not found. (%s)', $templateFile));
        }
        extract($this->templateVariables);
        ob_start();
        include $templateFile;
        return ob_get_clean();
    }

    /**
     * (Safely) outputs a template variable.

     * @param string $name
     * @param bool $secure
     * @return void
     */
    protected function out(string $name, $secure = true): void
    {
        if ($secure === true) {
            echo htmlentities($this->templateVariables[$name]);
        } else {
            echo $this->templateVariables[$name];
        }
    }
}
