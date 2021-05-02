<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer\Renderer;

use Bloatless\Endocore\Components\PhtmlRenderer\PhtmlRendererFactory;
use Bloatless\Endocore\Components\PhtmlRenderer\TemplatingException;

/**
 * Renders view-components within a view.
 *
 * @package Bloatless\Endocore\Components\PhtmlRenderer\Renderer
 */
class ViewComponentRenderer implements RendererInterface
{
    /**
     * @var PhtmlRendererFactory $phtmlRendererFactory
     */
    private PhtmlRendererFactory $phtmlRendererFactory;

    /**
     * A map of view-component-names and corresponding classes.
     *
     * @var array $viewComponentClasses
     */
    private array $viewComponentClasses;

    /**
     * Holds instances of view components.
     *
     * @var array $viewComponents
     */
    private array $viewComponents;

    /**
     * Data passed to the view.
     *
     * @var array $templateVariables
     */
    private array $templateVariables;

    /**
     * @param PhtmlRendererFactory $phtmlRendererFactory
     * @param array $viewComponentClasses
     */
    public function __construct(PhtmlRendererFactory $phtmlRendererFactory, array $viewComponentClasses)
    {
        $this->phtmlRendererFactory = $phtmlRendererFactory;
        $this->viewComponentClasses = $viewComponentClasses;
        $this->viewComponents = [];
        $this->templateVariables = [];
    }

    /**
     * Creates a view-component instance and returns the html-code of the rendered component.
     *
     * @param array $arguments
     * @param array $templateVariables
     * @return string
     * @throws TemplatingException
     */
    public function render(array $arguments, array $templateVariables): string
    {
        $this->templateVariables = $templateVariables;
        $componentHash = $arguments['hash'] ?? '';
        $componentType = $arguments['type'] ?? '';
        $componentAction = $arguments['action'] ?? '';
        $attributesString = $arguments['attributes'] ?? '';
        $data = $arguments['data'] ?? [];
        if (empty($componentHash)) {
            throw new TemplatingException('Component hash can not be empty.');
        }
        if (!isset($this->viewComponentClasses[$componentType])) {
            throw new TemplatingException(sprintf('Unknown view component type (%s)', $componentType));
        }

        $this->initComponent($componentType, $componentHash);
        switch ($componentAction) {
            case 'start':
                $attributes = $this->getAttributes($attributesString);
                $this->viewComponents[$componentHash]->setAttributes($attributes);
                $this->viewComponents[$componentHash]->setData($data);
                return $this->viewComponents[$componentHash]->start();
            case 'end':
                return $this->viewComponents[$componentHash]->end();
            default:
                throw new TemplatingException(sprintf('Invalid component action (%s)'));
        }
    }

    /**
     * Creates a new view-component instance.
     *
     * @param string $componentType
     * @param string $componentHash
     * @throws TemplatingException
     */
    private function initComponent(string $componentType, string $componentHash): void
    {
        if (isset($this->viewComponents[$componentHash])) {
            return;
        }

        $componentClass = $this->viewComponentClasses[$componentType];
        $phtmlRenderer = $this->phtmlRendererFactory->make();
        $this->viewComponents[$componentHash] = new $componentClass($phtmlRenderer);
    }

    /**
     * Prepares and returns the attributes passed to a view-component via arguments (within the view).
     *
     * @param string $attributeString
     * @return array
     */
    private function getAttributes(string $attributeString): array
    {
        if (empty($attributeString)) {
            return [];
        }

        $attributeString = base64_decode($attributeString);
        $attrCount = preg_match_all('/([\w:-]+)="([^"]+)"/Us', $attributeString, $attributeMatches, PREG_SET_ORDER);
        if ($attrCount === 0) {
            return [];
        }

        $attributes = [];
        foreach ($attributeMatches as $attr) {
            $attributes[$attr[1]] = $attr[2];
        }

        return $attributes;
    }
}
