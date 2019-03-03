<?php

declare(strict_types=1);

namespace Bloatless\Endocore;

use Bloatless\Endocore\Exception\Application\EndocoreException;

class Config
{
    /**
     * @var array $classes
     */
    protected $classes = [];

    /**
     * @var array $paths
     */
    protected $paths = [];

    /**
     * @var array $dbConfigs
     */
    protected $dbConfigs = [];

    /**
     * @var string $defaultDb
     */
    protected $defaultDb = '';

    /**
     * @var string $minLogLevel
     */
    protected $minLogLevel = 'debug';

    /**
     * Loads configuration from given config file.
     *
     * @param string $pathToConfigFile
     * @return Config
     * @throws EndocoreException
     */
    public function fromFile(string $pathToConfigFile): Config
    {
        if (!file_exists($pathToConfigFile)) {
            throw new EndocoreException(sprintf('Config file not found. (%s)', $pathToConfigFile));
        }
        $configData = include $pathToConfigFile;
        return $this->fromArray($configData);
    }

    /**
     * Loads configuration from given array.
     *
     * @param array $config
     * @throws EndocoreException
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

        // set database configurations:
        if (!empty($config['db'])) {
            $this->intiDbConfig($config['db']);
        }

        // set min. log level:
        if (!empty($config['logger']['min_level'])) {
            $this->setMinLogLevel($config['logger']['min_level']);
        }

        return $this;
    }

    /**
     * Initialized database configuration.
     *
     * @param array $dbConfig
     * @throws EndocoreException
     * @return void
     */
    private function intiDbConfig(array $dbConfig): void
    {
        if (empty($dbConfig['connections'])) {
            throw new EndocoreException('There needs to be at least one database connection. Check config.');
        }
        if (empty($dbConfig['default_connection'])) {
            throw new EndocoreException('Default database connection not set. Check config.');
        }
        foreach ($dbConfig['connections'] as $connectionName => $credentials) {
            $this->addDbConfig($connectionName, $credentials);
        }
        $this->setDefaultDatabase($dbConfig['default_connection']);
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
     * Returns a class name from configuration.
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

    /**
     * Adds a database configuration.
     *
     * @param string $connectionName
     * @param array $credentials
     * @return void
     */
    public function addDbConfig(string $connectionName, array $credentials): void
    {
        $this->dbConfigs[$connectionName] = $credentials;
    }

    /**
     * Retrieves a database configuration.
     *
     * @param string $connectionName
     * @return array
     */
    public function getDbConfig(string $connectionName): array
    {
        if (!isset($this->dbConfigs[$connectionName])) {
            throw new \InvalidArgumentException('Unknown database connection name. Please check config.');
        }
        return $this->dbConfigs[$connectionName];
    }

    /**
     * Retrieves default database configuration.
     *
     * @return array
     * @throws EndocoreException
     */
    public function getDefaultDbConfig(): array
    {
        if (empty($this->defaultDb)) {
            throw new EndocoreException('Default database is not set.');
        }
        return $this->dbConfigs[$this->defaultDb];
    }

    /**
     * Set default database/connection name.

     * @param string $connectionName
     * @return void
     */
    public function setDefaultDatabase(string $connectionName): void
    {
        if (!isset($this->dbConfigs[$connectionName])) {
            throw new \InvalidArgumentException(
                sprintf('Can not set default database to %s as this config does not exist.', $connectionName)
            );
        }
        $this->defaultDb = $connectionName;
    }

    /**
     * Retreives default database/connection name.
     *
     * @return string
     */
    public function getDefaultDatabase(): string
    {
        return $this->defaultDb;
    }

    /**
     * Sets min. log level for application.
     *
     * @param string $level
     * @return void
     */
    public function setMinLogLevel(string $level): void
    {
        $this->minLogLevel = $level;
    }

    /**
     * Retrieves min. log level.
     *
     * @return string
     */
    public function getMinLogLevel(): string
    {
        return $this->minLogLevel;
    }
}
