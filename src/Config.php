<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore;

class Config
{
    public $classes = [];

    public $paths = [];

    public function fromArray(array $config) : Config
    {
        // set renderer class:
        $rendererClass = $config['renderer'] ?? '\Nekudo\ShinyCore\Responder\PhtmlRenderer';
        $this->setClass('renderer', $rendererClass);

        // set paths:
        foreach ($config['paths'] as $name => $path) {
            $this->setPath($name, $path);
        }

        return $this;
    }

    public function getClass(string $name, string $default = '') : string
    {
        return $this->classes[$name] ?? $default;
    }

    public function setClass(string $name, string $class) : void
    {
        $this->classes[$name] = $class;
    }

    public function getPath(string $name, string $default = '') : string
    {
        return $this->paths[$name] ?? $default;
    }

    public function setPath(string $name, string $path) : void
    {
        $this->paths[$name] = $path;
    }
}
