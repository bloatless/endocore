<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Database\ConnectionAdapter\Mysql;
use Nekudo\ShinyCore\Exception\Application\DatabaseException;

class Factory
{
    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var array $builders
     */
    protected $builders = [];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Creates and returns a QueryBuilder instance. Each instance is only created once.
     *
     * @todo Add support for additional drivers.
     *
     * @param string $connectionName
     * @return QueryBuilder
     * @throws DatabaseException
     * @throws \Nekudo\ShinyCore\Exception\Application\ShinyCoreException
     */
    public function createDb(string $connectionName = ''): QueryBuilder
    {
        if (isset($this->builders[$connectionName])) {
            return $this->builders[$connectionName];
        }

        if (!empty($connectionName)) {
            $dbConfig = $this->config->getDbConfig($connectionName);
        } else {
            $dbConfig = $this->config->getDefaultDbConfig();
        }

        switch ($dbConfig['driver']) {
            case 'mysql':
                $adapter = new Mysql;
                break;
            default:
                throw new DatabaseException('Unsupported database driver. Check config.');
        }

        $connection = $adapter->connect($dbConfig);
        $builder = new QueryBuilder($connection);
        $this->addBuilder($connectionName, $builder);

        return $builder;
    }

    /**
     * Adds QueryBuilder instance to pool.
     *
     * @param string $connectionName
     * @param QueryBuilder $builder
     * @return void
     */
    public function addBuilder(string $connectionName, QueryBuilder $builder): void
    {
        $this->builders[$connectionName] = $builder;
    }

    /**
     * Retreives QueryBuilder from pool.
     *
     * @param string $connectionName
     * @return QueryBuilder
     * @throws DatabaseException
     */
    public function getBuilder(string $connectionName): QueryBuilder
    {
        if (!isset($this->builders[$connectionName])) {
            throw new DatabaseException(sprintf('Connection (%s) not found in pool.', $connectionName));
        }
        return $this->builders[$connectionName];
    }
}
