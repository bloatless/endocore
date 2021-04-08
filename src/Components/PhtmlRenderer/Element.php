<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer;

/**
 * An Element is a part of a view component.
 * Elements can be placed inside view components usesing the <el-ELEMENT-NAME> syntax.
 *
 * @package Bloatless\Endocore\Components\PhtmlRenderer
 */
class Element
{
    /**
     * Name of the element. Extracted from the html-tag.
     *
     * @var string $name
     */
    private string $name = '';

    /**
     * Content of the element. (Everything between opening and closing html-tag)
     *
     * @var string $content
     */
    private string $content = '';

    /**
     * List of attribute passed to the element via html-attributes.
     *
     * @var array $attributes
     */
    private array $attributes = [];

    public function __construct(string $name, string $content, array $attributes = [])
    {
        $this->setName($name);
        $this->setContent($content);
        $this->setAttributes($attributes);
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $attributeName
     * @return bool
     */
    public function hasAttribute(string $attributeName): bool
    {
        return isset($this->attributes[$attributeName]);
    }

    /**
     * @param string $attributeName
     * @param null $default
     * @return mixed
     */
    public function getAttribute(string $attributeName, $default = null)
    {
        if ($this->hasAttribute($attributeName)) {
            return $this->attributes[$attributeName];
        }

        return $default;
    }

    /**
     * @param string $attributeName
     * @param $attributeValue
     * @return void
     */
    public function setAttribute(string $attributeName, $attributeValue): void
    {
        $this->attributes[$attributeName] = $attributeValue;
    }
}