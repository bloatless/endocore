<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer\Renderer;

use Bloatless\Endocore\Components\PhtmlRenderer\TemplatingException;

class CustomTagRenderer implements RendererInterface
{
    private JsStack $jsStack;

    public function __construct()
    {
        $this->jsStack = JsStack::getInstance();
    }

    /**
     * Handles renderer calls injected by CustomTagPreCompiler.
     *
     * @param array $arguments
     * @param array $templateVariables
     * @return string
     * @throws TemplatingException
     */
    public function render(array $arguments, array $templateVariables): string
    {
        if (!isset($arguments['action'])) {
            return '';
        }

        switch ($arguments['action']) {
            case 'addJs':
                $this->addToJsStack($arguments['js'] ?? '');
                return '';
            case 'injectJs':
                return $this->echoJsStack();
            default:
                throw new TemplatingException(sprintf('Invalid CustomTagRenderer action: %s', $arguments['action']));
        }
    }

    /**
     * Adds javascript-code to stack.
     *
     * @param string $jsCode
     */
    private function addToJsStack(string $jsCode): void
    {
        $this->jsStack->add(base64_decode($jsCode));
    }

    /**
     * Injects javascript code from stack into view.
     *
     * @return string
     */
    private function echoJsStack(): string
    {
        $jsCode = implode("\r\n ", $this->jsStack->all());

        return '<script>' . $jsCode . '</script>';
    }
}
