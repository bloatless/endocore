<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer\PreCompiler;

/**
 * Handles mustache tags within a view. (e.g. {{ $foo }})
 *
 * @package Bloatless\Endocore\Components\PhtmlRenderer\PreCompiler
 */
class MustachePreCompiler implements PreCompilerInterface
{
    /**
     * Holds key-value pairs to be replaced.
     *
     * @var array $replacements
     */
    private $replacements = [];

    /**
     * Replaces mustache-tags with actual php-code.
     *
     * @param string $content
     * @param array $templateVariables
     * @return string
     */
    public function compile(string $content, array $templateVariables = []): string
    {
        $this->replacements = [];
        $this->parseOutTags($content);
        $this->parseUnescapedOutTags($content);
        $content = strtr($content, $this->replacements);

        return $content;
    }

    /**
     * Parses simple "echo" mustache tags. e.g. {{ $foo }}
     *
     * @param string $source
     * @return void
     */
    private function parseOutTags(string $source): void
    {
        $outTagCount = preg_match_all('/\{\{\s([^}]+)\s\}\}/Us', $source, $matches, PREG_SET_ORDER);
        if ($outTagCount === 0) {
            return;
        }

        $this->addReplacements($matches);
    }

    /**
     * Parses "unescaped echo" tags. E.g. {!! $foo !!}
     *
     * @param string $source
     * @return void
     */
    private function parseUnescapedOutTags(string $source): void
    {
        $outTagCount = preg_match_all('/\{\!\!\s([^}]+)\s\!\!\}/Us', $source, $matches, PREG_SET_ORDER);
        if ($outTagCount === 0) {
            return;
        }

        $this->addReplacements($matches, false);
    }

    /**
     * Converts matches form regular expression to replacable key-value pairs.
     *
     * @param array $matches
     * @param bool $escaped
     */
    private function addReplacements(array $matches, bool $escaped = true): void
    {
        foreach ($matches as $match) {
            $tag = $match[0];
            $varName = $match[1];
            if ($escaped === true) {
                $this->replacements[$tag] = sprintf('<?php echo htmlentities(%s); ?>', $varName);
            } else {
                $this->replacements[$tag] = sprintf('<?php echo %s; ?>', $varName);
            }
        }
    }
}
