<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer\PreCompiler;

use Bloatless\Endocore\Components\PhtmlRenderer\TemplatingException;

class ViewComponentPreCompiler implements PreCompilerInterface
{
    /**
     * Data passed to the view.
     *
     * @var array $templateVariables
     */
    protected array $templateVariables = [];

    /**
     * Pre-compiles view-components.
     *
     * @param string $viewContent
     * @param array $templateVariables
     * @return string
     */
    public function compile(string $viewContent, array $templateVariables = []): string
    {
        $this->templateVariables = $templateVariables;
        $viewContent = $this->parseSelfClosingTags($viewContent);
        $viewContent = $this->parseOpenCloseTags($viewContent);

        return $viewContent;
    }

    /**
     * Finds self-closing view-component tags and replaces them with php-code.
     *
     * @param $content
     * @return string
     */
    private function parseSelfClosingTags($content): string
    {
        $cnt = preg_match_all('/<vc-(?<component>[\w-]+)(?<attributes>\s[^>]*)?\/>/Us', $content, $matches, PREG_SET_ORDER);
        if ($cnt === 0) {
            return $content;
        }

        $tagCounts = [];
        foreach ($matches as $match) {
            $componentName = $match['component'];
            if (!isset($tagCounts[$componentName])) {
                $tagCounts[$componentName] = 0;
            }
            $attributes = $this->getPrimitiveAttributes($match['attributes']);
            $attributes = base64_encode($attributes);
            $data = $this->getDataAttributes($match['attributes']);
            $componentHash = $this->getComponentHash($componentName, 0, $tagCounts[$componentName]);
            $uniqueTag = sprintf(
                '<?php $this->call(\'viewComponent\', [\'hash\' => \'%s\', \'type\' => \'%s\', \'action\' => \'start\', \'attributes\' => \'%s\'], \'data\' => %s]); ?>',
                $componentHash,
                $componentName,
                $attributes,
                $data,
            );
            $uniqueTag .= sprintf(
                '<?php $this->call(\'viewComponent\', [\'hash\' => \'%s\', \'type\' => \'%s\', \'action\' => \'end\']); ?>',
                $componentHash,
                $componentName,
            );
            $tagPattern = '#' . preg_quote($match[0]) . '#';
            $content = preg_replace($tagPattern, $uniqueTag, $content, 1);
            $tagCounts[$componentName]++;
        }

        return $content;
    }

    /**
     * Finds opening and closing view-component tags and replaces them with php-code.
     *
     * @param $content
     * @return string
     * @throws TemplatingException
     */
    private function parseOpenCloseTags($content): string
    {
        // collect opening tags
        $openingTagsCount = preg_match_all(
            '/<vc-(?<component>[\w-]+)(?<attributes>\s.*)?(?<!-)>/Us',
            $content,
            $openingTags,
            PREG_OFFSET_CAPTURE|PREG_SET_ORDER
        );

        // collect closing tags
        $closingTagsCount = preg_match_all(
            '/<\/vc-(?<component>[\w-]+)>/Us',
            $content,
            $closingTags,
            PREG_OFFSET_CAPTURE|PREG_SET_ORDER
        );

        if ($openingTagsCount !== $closingTagsCount) {
            throw new TemplatingException(
                'View component tag-count mismatch. (Number of opening tags does not match number of closing tags.)'
            );
        }

        $tags = [];
        foreach ($openingTags as $item) {
            $tags[] = (object) [
                'tag' => $item[0][0],
                'offset' => $item[0][1],
                'component' => $item['component'][0],
                'attributes' => $this->getPrimitiveAttributes($item['attributes'][0] ?? ''),
                'data' => $this->getDataAttributes($item['attributes'][0] ?? ''),
                'type' => 'opening',
            ];
        }
        foreach ($closingTags as $item) {
            $tags[] = (object) [
                'tag' => $item[0][0],
                'offset' => $item[0][1],
                'component' => $item['component'][0],
                'type' => 'closing',
            ];
        }

        // order by offset
        usort($tags, function ($a, $b) {
            return $a->offset <=> $b->offset;
        });

        // add level and count information
        $levels = [];
        $itemCounts = [];
        foreach ($tags as $i => $tag) {
            $component = $tag->component;
            if (!isset($levels[$component])) {
                $levels[$component] = 0;
            }
            if (!isset($itemCounts[$component])) {
                $itemCounts[$component] = [];
            }

            if ($tag->type === 'opening') {
                // add level
                $level = $levels[$component];
                $tags[$i]->level = $level;
                $levels[$component]++;

                // add count on level
                if (!isset($itemCounts[$component][$level])) {
                    $itemCounts[$component][$level] = 0;
                }
                $tags[$i]->levelItem = $itemCounts[$component][$level];
            }
            if ($tag->type === 'closing') {
                $levels[$component]--;
                $level = $levels[$component];
                $tags[$i]->level = $level;

                $tags[$i]->levelItem = $itemCounts[$component][$level];
                $itemCounts[$component][$level]++;

            }
        }

        // replace component tags with php-code
        foreach ($tags as $tag) {
            $componentHash = $this->getComponentHash($tag->component, $tag->level, $tag->levelItem);
            if ($tag->type === 'opening') {
                $attributes = base64_encode(trim($tag->attributes));
                $uniqueTag = sprintf(
                    '<?php $this->call(\'viewComponent\', [\'hash\' => \'%s\', \'type\' => \'%s\', \'action\' => \'start\', \'attributes\' => \'%s\', \'data\' => %s]); ?>',
                    $componentHash,
                    $tag->component,
                    $attributes,
                    $tag->data,
                );
            }
            if ($tag->type === 'closing') {
                $uniqueTag = sprintf(
                    '<?php $this->call(\'viewComponent\', [\'hash\' => \'%s\', \'type\' => \'%s\', \'action\' => \'end\']); ?>',
                    $componentHash,
                    $tag->component,
                );
            }
            $tagPattern = '#' . preg_quote($tag->tag) . '#';
            $content = preg_replace($tagPattern, $uniqueTag, $content, 1);
        }

        return $content;
    }

    /**
     * Generate a unique hash from a view-component tag (and its attributes).
     *
     * @param string $componentType
     * @param int $level
     * @param int $item
     * @return string
     */
    private function getComponentHash(string $componentType, int $level, int $item): string
    {
        return md5(implode('|', [$componentType, $level, $item]));
    }

    /**
     * Collects primitive attributes from all attributes passed to the component.
     * Primitive attributes are simple text attributes like e.g.: foo="bar"
     *
     * @param string $rawAttributes
     * @return string
     */
    private function getPrimitiveAttributes(string $rawAttributes): string
    {
        $attrCount = preg_match_all('/([\w:-]+)="([^"]+)"/Us', $rawAttributes, $attributeMatches, PREG_SET_ORDER);
        if ($attrCount === 0) {
            return '';
        }

        $attributes = [];
        foreach ($attributeMatches as $attr) {
            if (substr($attr[1], 0, 1) === ':') {
                continue;
            }
            $attributes[] = trim($attr[0]);
        }

        return implode(' ', $attributes);
    }

    /**
     * Collects data attributes from all attributes passed to the component.
     * Data attributes start with a colon and contain a variable, like e.g: :foo="$bar"
     * The response of this method is a string containing an php-array, e.g: ['foo' => $bar]
     *
     * @param string $rawAttributes
     * @return string
     */
    private function getDataAttributes(string $rawAttributes): string
    {
        $attrCount = preg_match_all('/(:[\w-]+)="([^"]+)"/Us', $rawAttributes, $attributeMatches, PREG_SET_ORDER);
        if ($attrCount === 0) {
            return '[]';
        }

        $dataAttributes = '[';
        foreach ($attributeMatches as $attr) {
            $attrName = substr($attr[1], 1);
            $attrValue = $attr[2];
            $dataAttributes .= sprintf("'%s' => %s,", $attrName, $attrValue);
        }
        $dataAttributes .= ']';

        return $dataAttributes;
    }
}
