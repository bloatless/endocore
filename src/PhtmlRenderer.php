<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Interfaces\RendererInterface;

class PhtmlRenderer implements RendererInterface
{
    protected $config;

    protected $layout = '';

    protected $view = '';

    protected $templateVariables = [];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getLayout(): string
    {
        return $this->layout;
    }

    public function setLayout(string $layout)
    {
        $this->layout = $layout;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function setView(string $view)
    {
        $this->view = $view;
    }

    public function assign(array $pairs): void
    {
        $this->templateVariables = array_merge($this->templateVariables, $pairs);
    }

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

    protected function renderView(): string
    {
        $viewFile = $this->config->getPath('views') . '/' . $this->view . '.phtml';
        $viewContent = file_get_contents($viewFile);
        if (preg_match('/<!-- extends "(.+)" -->/Usi', $viewContent, $matches) === 1) {
            $this->setLayout($matches[1]);
        }

        return $this->renderFile($viewFile);
    }

    protected function renderLayout(string $content): string
    {
        $content = str_replace('<!-- extends "' . $this->layout . '" -->', '', $content);
        $content = trim($content);
        $this->assign(['content' => $content]);
        $layoutFile = $this->config->getPath('layouts') . '/' . $this->layout . '.phtml';
        return $this->renderFile($layoutFile);
    }

    protected function renderFile(string $templateFile): string
    {
        if (!file_exists($templateFile)) {
            // @todo handle error
        }
        extract($this->templateVariables);
        ob_start();
        include $templateFile;
        return ob_get_clean();
    }

    protected function out(string $name, $secure = true)
    {
        if ($secure === true) {
            echo htmlentities($this->templateVariables[$name]);
        } else {
            echo $this->templateVariables[$name];
        }
    }
}
