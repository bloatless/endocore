<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\QueryBuilder\QueryBuilder;

use Bloatless\Endocore\Components\QueryBuilder\StatementBuilder\StatementBuilder;
use Bloatless\Endocore\Components\QueryBuilder\Exception\DatabaseException;

abstract class QueryBuilder
{
    /**
     * @var \PDO $connection
     */
    protected $connection;

    /**
     * @var StatementBuilder $statementBuilder
     */
    protected $statementBuilder;

    /**
     * @param \PDO $connection
     * @param StatementBuilder $statementBuilder
     */
    public function __construct(\PDO $connection, StatementBuilder $statementBuilder)
    {
        $this->connection = $connection;
        $this->statementBuilder = $statementBuilder;
    }

    /**
     * Sets statement builder.
     *
     * @param StatementBuilder $statementBuilder
     * @return void
     */
    public function setStatementBuilder(StatementBuilder $statementBuilder): void
    {
        $this->statementBuilder = $statementBuilder;
    }

    /**
     * Retrieves statement builder.
     *
     * @return StatementBuilder
     */
    public function getStatementBuilder(): StatementBuilder
    {
        return $this->statementBuilder;
    }

    /**
     * Builds the SQL statement and converts it into an PDO prepared statement object.
     *
     * @return \PDOStatement
     * @throws DatabaseException()
     */
    protected function provideStatement(): \PDOStatement
    {
        $sqlStatement = $this->buildStatement();
        $bindingValues = $this->statementBuilder->getBindingValues();
        return $this->prepareStatement($sqlStatement, $bindingValues);
    }

    /**
     * Coverts an SQL statement into PDO statement object and binds all values.
     *
     * @param string $sqlStatement
     * @param array $bindingValues
     * @return \PDOStatement
     * @throws DatabaseException()
     */
    protected function prepareStatement(string $sqlStatement, array $bindingValues): \PDOStatement
    {
        $pdoStatement = $this->connection->prepare($sqlStatement);
        if ($pdoStatement === false) {
            $this->throwError($this->connection->errorInfo());
        }

        foreach ($bindingValues as $key => $value) {
            if (is_int($value)) {
                $pdoStatement->bindValue($key, $value, \PDO::PARAM_INT);
            } elseif (is_bool($value)) {
                $pdoStatement->bindValue($key, $value, \PDO::PARAM_BOOL);
            } elseif (is_null($value)) {
                $pdoStatement->bindValue($key, $value, \PDO::PARAM_NULL);
            } else {
                $pdoStatement->bindValue($key, $value, \PDO::PARAM_STR);
            }
        }

        return $pdoStatement;
    }

    /**
     * Executes a PDO statement.
     *
     * @param \PDOStatement $pdoStatement
     * @return \PDOStatement
     * @throws DatabaseException()
     */
    public function execute(\PDOStatement $pdoStatement): \PDOStatement
    {
        $result = $pdoStatement->execute();
        if ($result === false) {
            $this->throwError($pdoStatement->errorInfo());
        }

        return $pdoStatement;
    }

    /**
     * Throws a query exception with error message from PDO.
     *
     * @param array $errorInfo
     * @throws DatabaseException()
     * @return void
     */
    protected function throwError(array $errorInfo): void
    {
        $errorMessage = $errorInfo[2] ?? 'Unspecified SQL error.';
        throw new DatabaseException($errorMessage);
    }

    /**
     * Builds an SQL statement using the statement builder.
     *
     * @return string
     */
    abstract protected function buildStatement(): string;

    /**
     * Resets all previously added values.
     *
     * @return void
     */
    abstract public function reset(): void;
}
