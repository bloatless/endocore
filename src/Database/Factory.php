<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Database\ConnectionAdapter\PdoMysql;
use Nekudo\ShinyCore\Database\QueryBuilder\SelectBuilder;
use Nekudo\ShinyCore\Exception\Application\DatabaseException;

class Factory
{
    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var array $connections
     */
    protected $connections = [];

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function makeInsert(string $connectionName = '')
    {

    }

    public function makeSelect(string $connectionName = ''): SelectBuilder
    {
        $connection = $this->provideConnection($connectionName);
        return new SelectBuilder($connection);
    }

    public function makeUpdate(string $connectionName = '')
    {

    }

    public function makeDelete(string $connectionName = '')
    {

    }

    public function provideConnection(string $connectionName = ''): \PDO
    {
        if (empty($connectionName)) {
            $connectionName = $this->config->getDefaultDatabase();
        }
        if ($this->hasConnection($connectionName) === true) {
            return $this->getConnection($connectionName);
        }

        $dbConfig = $this->config->getDbConfig($connectionName);

        switch ($dbConfig['driver']) {
            case 'mysql':
                $adapter = new PdoMysql;
                break;
            default:
                throw new DatabaseException('Unsupported database driver. Check config.');
        }

        $connection = $adapter->connect($dbConfig);
        $this->addConnection($connectionName, $connection);

        return $connection;
    }

    public function hasConnection(string $connectionName): bool
    {
        return isset($this->connections[$connectionName]);
    }

    public function addConnection(string $connectionName, \PDO $connection): void
    {
        $this->connections[$connectionName] = $connection;
    }

    public function getConnection(string $connectionName): \PDO
    {
        if (!isset($this->connections[$connectionName])) {
            throw new DatabaseException(sprintf('Connection (%s) not found in pool.', $connectionName));
        }
        return $this->connections[$connectionName];
    }
}
