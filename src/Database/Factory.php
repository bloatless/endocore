<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Database\ConnectionAdapter\PdoMysql;
use Nekudo\ShinyCore\Database\QueryBuilder\InsertQueryBuilder;
use Nekudo\ShinyCore\Database\QueryBuilder\SelectQueryBuilder;
use Nekudo\ShinyCore\Database\QueryBuilder\UpdateQueryBuilder;
use Nekudo\ShinyCore\Database\StatementBuilder\InsertStatementBuilder;
use Nekudo\ShinyCore\Database\StatementBuilder\SelectStatementBuilder;
use Nekudo\ShinyCore\Database\StatementBuilder\UpdateStatementBuilder;
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

    /**
     * Creates a new InsertQueryBuilder instance.
     *
     * @param string $connectionName
     * @return InsertQueryBuilder
     * @throws DatabaseException
     */
    public function makeInsert(string $connectionName = '')
    {
        $connection = $this->provideConnection($connectionName);
        $statementBuilder = new InsertStatementBuilder;
        return new InsertQueryBuilder($connection, $statementBuilder);
    }

    /**
     * Creates a new SelectQueryBuilder instance.
     *
     * @param string $connectionName
     * @return SelectQueryBuilder
     * @throws DatabaseException
     */
    public function makeSelect(string $connectionName = ''): SelectQueryBuilder
    {
        $connection = $this->provideConnection($connectionName);
        $statementBuilder = new SelectStatementBuilder;
        return new SelectQueryBuilder($connection, $statementBuilder);
    }

    /**
     * Creates a new UpdateQueryBuilder instance.
     *
     * @param string $connectionName
     * @throws DatabaseException
     * @return UpdateQueryBuilder
     */
    public function makeUpdate(string $connectionName = ''): UpdateQueryBuilder
    {
        $connection = $this->provideConnection($connectionName);
        $statementBuilder = new UpdateStatementBuilder;
        return new UpdateQueryBuilder($connection, $statementBuilder);
    }

    /**
     * Creates a new UpdateQueryBuilder instance.
     *
     * @param string $connectionName
     */
    public function makeDelete(string $connectionName = '')
    {
    }

    /**
     * Provides a database connection (PDO object).
     *
     * @param string $connectionName
     * @return \PDO
     * @throws DatabaseException
     */
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

    /**
     * Checks if database connection with given name exists.
     *
     * @param string $connectionName
     * @return bool
     */
    public function hasConnection(string $connectionName): bool
    {
        return isset($this->connections[$connectionName]);
    }

    /**
     * Adds database connection to pool.
     *
     * @param string $connectionName
     * @param \PDO $connection
     * @return void
     */
    public function addConnection(string $connectionName, \PDO $connection): void
    {
        $this->connections[$connectionName] = $connection;
    }

    /**
     * Retrieves a database connection.
     *
     * @param string $connectionName
     * @return \PDO
     * @throws DatabaseException
     */
    public function getConnection(string $connectionName): \PDO
    {
        if (!isset($this->connections[$connectionName])) {
            throw new DatabaseException(sprintf('Connection (%s) not found in pool.', $connectionName));
        }
        return $this->connections[$connectionName];
    }
}
