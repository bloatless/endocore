<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\QueryBuilder;

use Nekudo\ShinyCore\Database\StatementBuilder\StatementBuilder;
use Nekudo\ShinyCore\Exception\Application\DatabaseQueryException;

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
     * @throws DatabaseQueryException
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
     * @throws DatabaseQueryException
     */
    protected function prepareStatement(string $sqlStatement, array $bindingValues): \PDOStatement
    {
        $pdoStatement = $this->connection->prepare($sqlStatement);

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

        $result = $pdoStatement->execute();
        if ($result === false) {
            $pdoError = $pdoStatement->errorInfo();
            $errorMessage = $pdoError[2] ?? 'Unspecified SQL error.';
            throw new DatabaseQueryException($errorMessage);
        }

        return $pdoStatement;
    }

    /**
     * Builds an SQL statement using the statement builder.
     *
     * @return string
     */
    abstract protected function buildStatement(): string;
}
