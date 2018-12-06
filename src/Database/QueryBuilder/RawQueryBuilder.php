<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\QueryBuilder;

/**
 * @property \Nekudo\ShinyCore\Database\StatementBuilder\RawStatementBuider $statementBuilder
 */
class RawQueryBuilder extends QueryBuilder
{
    /**
     * @var string $statement
     */
    protected $statement;

    /**
     * @var array $bindings
     */
    protected $bindings = [];

    /**
     * Prepares a raw statement.
     *
     * @param string $statement
     * @param array $bindings
     * @return RawQueryBuilder
     */
    public function prepare(string $statement, array $bindings = []): RawQueryBuilder
    {
        $this->statement = $statement;
        $this->bindings = $bindings;

        return $this;
    }

    /**
    * Executes raw statement and returns all matching rows as array of objects.
    *
    * @return array
    * @throws \Nekudo\ShinyCore\Exception\Application\DatabaseException
    */
    public function get(): array
    {
        $pdoStatement = $this->provideStatement();
        $pdoStatement = $this->execute($pdoStatement);
        return $pdoStatement->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Executes a raw statement.
     *
     * @throws \Nekudo\ShinyCore\Exception\Application\DatabaseException
     */
    public function run(): void
    {
        $pdoStatement = $this->provideStatement();
        $this->execute($pdoStatement);
    }

    /**
     * Builds/Prepares the raw statement for execution.
     *
     * @return string
     */
    protected function buildStatement(): string
    {
        $this->statementBuilder->prepareRawStatement($this->statement, $this->bindings);
        return $this->statementBuilder->getStatement();
    }
}
