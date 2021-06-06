<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer\PreCompiler;

class CustomTagPreCompiler implements PreCompilerInterface
{
    /**
     * Replaces custom-tags with php-code in view-files.
     *
     * @param string $content
     * @param array $templateVariables
     * @return string
     */
    public function compile(string $content, array $templateVariables = []): string
    {
        $content = $this->extractJs($content);
        $content = $this->replaceInjectTag($content);

        return $content;
    }

    /**
     * Replaces <script type="extract-js"> tags.
     *
     * @param string $content
     * @return string
     */
    private function extractJs(string $content): string
    {
        $pattern = '/<script data-ecc="extract-js">(?<js>.*)<\/script>/Us';
        $matchCount = preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
        if ($matchCount === 0) {
            return $content;
        }

        $phpCodePattern = '<?php $this->call(\'customTags\', [\'action\' => \'addJs\', \'js\' => \'%s\']); ?>';
        foreach ($matches as $match) {
            $tag = $match[0];
            $js = base64_encode(trim($match['js']));
            $tagReplacement = sprintf($phpCodePattern, $js);
            $content = str_replace($tag, $tagReplacement, $content);
        }

        return $content;
    }

    /**
     * Replaces "{% inject-js %}" tags.
     *
     * @param string $content
     * @return string
     */
    private function replaceInjectTag(string $content): string
    {
        $phpCodePattern = '<?php $this->call(\'customTags\', [\'action\' => \'injectJs\']); ?>';

        return str_replace('{% inject-js %}', $phpCodePattern, $content);
    }
}
