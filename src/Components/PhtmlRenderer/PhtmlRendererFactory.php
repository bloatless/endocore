<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\PhtmlRenderer;

use Bloatless\Endocore\Components\PhtmlRenderer\PreCompiler\CustomTagPreCompiler;
use Bloatless\Endocore\Components\PhtmlRenderer\PreCompiler\SubviewPreCompiler;
use Bloatless\Endocore\Components\PhtmlRenderer\PreCompiler\LayoutPreCompiler;
use Bloatless\Endocore\Components\PhtmlRenderer\PreCompiler\MustachePreCompiler;
use Bloatless\Endocore\Components\PhtmlRenderer\PreCompiler\ViewComponentPreCompiler;
use Bloatless\Endocore\Components\PhtmlRenderer\Renderer\CustomTagRenderer;
use Bloatless\Endocore\Components\PhtmlRenderer\Renderer\SubviewRenderer;
use Bloatless\Endocore\Components\PhtmlRenderer\Renderer\ViewComponentRenderer;
use Bloatless\Endocore\Contracts\Components\FactoryContract;

class PhtmlRendererFactory implements FactoryContract
{
    /**
     * @var array $config
     */
    protected array $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Creates and returns a new PhtmlRenderer instance.
     *
     * @return PhtmlRenderer
     * @throws TemplatingException
     */
    public function make(): PhtmlRenderer
    {
        if (empty($this->config['renderer'])) {
            throw new TemplatingException('Can not provide component "PhtmlRenderer". Configuration missing.');
        }

        $pathViews = $this->config['renderer']['path_views'] ?? '';
        $compilePath = $this->config['renderer']['compile_path'] ?? '';
        $viewComponents = $this->config['renderer']['view_components'] ?? [];

        // prepare renderer
        $viewRenderer = new ViewRenderer();
        $subviewRenderer = new SubviewRenderer($this);
        $viewComponentRenderer = new ViewComponentRenderer($this, $viewComponents);
        $customTagRenderer = new CustomTagRenderer();
        $viewRenderer->addRenderer('subview', $subviewRenderer);
        $viewRenderer->addRenderer('viewComponent', $viewComponentRenderer);
        $viewRenderer->addRenderer('customTags', $customTagRenderer);

        // prepare pre-compiler
        $layoutPreCompiler = new LayoutPreCompiler();
        $layoutPreCompiler->setViewPath($pathViews);
        $mustachePreCompiler = new MustachePreCompiler();
        $subviewPreCompiler = new SubviewPreCompiler();
        $viewComponentPreCompiler = new ViewComponentPreCompiler();
        $customTagPreCompiler = new CustomTagPreCompiler();

        $renderer = new PhtmlRenderer($viewRenderer);
        $renderer->setViewPath($pathViews);
        $renderer->setCompilePath($compilePath);
        $renderer->addPreCompiler($layoutPreCompiler);
        $renderer->addPreCompiler($subviewPreCompiler);
        $renderer->addPreCompiler($mustachePreCompiler);
        $renderer->addPreCompiler($viewComponentPreCompiler);
        $renderer->addPreCompiler($customTagPreCompiler);

        return $renderer;
    }
}
