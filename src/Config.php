<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Exception\Application\ShinyCoreException;

class Config
{
    /**
     * @var array $classes
     */
    public $classes = [];

    /**
     * @var array $paths
     */
    public $paths = [];

    /**
     * Loads configuration from given config file.
     *
     * @param string $pathToConfigFile
     * @return Config
     * @throws ShinyCoreException
     */
    public function fromFile(string $pathToConfigFile): Config
    {
        if (!file_exists($pathToConfigFile)) {
            throw new ShinyCoreException(sprintf('Config file not found. (%s)', $pathToConfigFile));
        }
        $configData = include $pathToConfigFile;
        return $this->fromArray($configData);
    }

    /**
     * Loads configuration from given array.
     *
     * @param array $config
     * @return Config
     */
    public function fromArray(array $config): Config
    {
        // set classes:
        foreach ($config['classes'] as $name => $class) {
            $this->setClass($name, $class);
        }

        // set paths:
        foreach ($config['paths'] as $name => $path) {
            $this->setPath($name, $path);
        }

        return $this;
    }

    /**
     * Adds a class name to configuration.
     *
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getClass(string $name, string $default = ''): string
    {
        return $this->classes[$name] ?? $default;
    }

    /**
     * Returns a class name from configuraiton.
     *
     * @param string $name
     * @param string $class
     */
    public function setClass(string $name, string $class): void
    {
        $this->classes[$name] = $class;
    }

    /**
     * Adds a path to configuration.
     *
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getPath(string $name, string $default = ''): string
    {
        return $this->paths[$name] ?? $default;
    }

    /**
     * Returns a path from configuration.
     *
     * @param string $name
     * @param string $path
     */
    public function setPath(string $name, string $path): void
    {
        $this->paths[$name] = $path;
    }
}
