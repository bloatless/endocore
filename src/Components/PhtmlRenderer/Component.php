<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer;

/**
 * This is the base class for each view component.
 *
 * @package Bloatless\Endocore\Components\PhtmlRenderer
 */
abstract class Component
{
    /**
     * An instance of the phtml-renderer is required so a view-component can render it's own views in a separate scope.
     *
     * @var PhtmlRenderer $phtmlRenderer
     */
    protected PhtmlRenderer $phtmlRenderer;

    /**
     * The content of a view-component. (The content is everything between the open and close tags of the component.)
     * @var string $content
     */
    protected string $content = '';

    /**
     * Attributes passed to the component.
     *
     * @var array $attributes
     */
    protected array $attributes = [];

    /**
     * Data passed to the component.
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Elements found within the component content.
     *
     * @var array $elements
     */
    protected array $elements = [];

    public function __construct(PhtmlRenderer $phtmlRenderer)
    {
        $this->phtmlRenderer = $phtmlRenderer;
    }

    /**
     * @param string $content
     * @return void
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param array $attributes
     * @return void
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * @param array $data
     * @return void
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param string $name
     * @param Element $element
     * @return void
     */
    public function addElement(string $name, Element $element): void
    {
        $this->elements[$name] = $element;
    }

    /**
     * @param string $name
     * @return Element|null
     */
    public function getElement(string $name): ?Element
    {
        return $this->elements[$name] ?? null;
    }

    /**
     * @param string $elementName
     * @return bool
     */
    public function hasElement(string $elementName): bool
    {
        return isset($this->elements[$elementName]);
    }

    /**
     * This method is called from a compiled view for each start-tag of a view-component.
     * It starts output buffering for the component content.
     *
     * @return string
     */
    public function start(): string
    {
        ob_start();

        return '';
    }

    /**
     * This method is called from a compiled view for each end tag of a view-component.
     * It closes the output buffer for the component and injects the content into the component. The component
     * is than invoked.
     *
     * @return string
     */
    public function end(): string
    {
        $rawContent = ob_get_clean();
        $content = $this->stripElementsFromContent($rawContent);
        $this->setContent($content);

        return $this->__invoke();
    }

    /**
     * Renders a view form within a view-component.
     *
     * @param string $viewName
     * @param array $templateVariables
     * @return string
     * @throws TemplatingException
     */
    protected function render(string $viewName, array $templateVariables = []): string
    {
        return $this->phtmlRenderer->render($viewName, $templateVariables);
    }

    /**
     * Collects elements within a view component and removes the code from the component-content.
     *
     * @param string $content
     * @return string
     */
    private function stripElementsFromContent(string $content): string
    {
        $elCount = preg_match_all(
            '/<el-(?<name>[\w-]+)(?<attributes>\s[^>]*)?>(?<content>.*)<\/el-\1>/Us',
            $content,
            $matches,
            PREG_SET_ORDER
        );
        if ($elCount === 0) {
            return $content;
        }

        foreach ($matches as $match) {
            $elAttributes = $this->getAttributes($match['attributes']);
            $this->addElement(
                $match['name'],
                (new Element($match['name'], $match['content'], $elAttributes))
            );
            $content = str_replace($match[0], '', $content);
        }

        return $content;
    }

    /**
     * Collects attribute data from an element-tag within a view-component.
     *
     * @param string $attributeString
     * @return array
     */
    private function getAttributes(string $attributeString): array
    {
        if (empty($attributeString)) {
            return [];
        }
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

    abstract public function __invoke(): string;
}
