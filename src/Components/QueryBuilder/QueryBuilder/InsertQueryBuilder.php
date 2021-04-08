<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\QueryBuilder\QueryBuilder;

/**
 * @property \Bloatless\Endocore\Components\QueryBuilder\StatementBuilder\InsertStatementBuilder $statementBuilder
 */
class InsertQueryBuilder extends QueryBuilder
{
    /**
     * @var array $flags
     */
    protected $flags = [];

    /**
     * @var string $into
     */
    protected $into = '';

    /**
     * @var array $rows
     */
    protected $rows = [];

    /**
     * Sets the ignore flag.
     *
     * @return InsertQueryBuilder
     */
    public function ignore(): InsertQueryBuilder
    {
        $this->flags['ignore'] = true;
        return $this;
    }

    /**
     * Sets table name to insert data into.
     *
     * @param string $table
     * @return InsertQueryBuilder
     */
    public function into(string $table): InsertQueryBuilder
    {
        $this->into = $table;
        return $this;
    }

    /**
     * Inserts new row into database and returns insert-id.
     *
     * @param array $data
     * @return int
     * @throws \Bloatless\Endocore\Components\QueryBuilder\Exception\DatabaseException
     */
    public function row(array $data): int
    {
        array_push($this->rows, $data);
        $pdoStatement = $this->provideStatement();
        $this->execute($pdoStatement);
        return $this->getLastInsertId();
    }

    /**
     * Inserts multiple rows into database.
     *
     * @param array $data
     * @throws \Bloatless\Endocore\Components\QueryBuilder\Exception\DatabaseException
     */
    public function rows(array $data): void
    {
        $this->rows = $data;
        $pdoStatement = $this->provideStatement();
        $this->execute($pdoStatement);
    }

    /**
     * Fetches last insert-id.
     *
     * @return int
     */
    public function getLastInsertId(): int
    {
        return (int) $this->connection->lastInsertId();
    }

    /**
     * @inheritdoc
     */
    public function reset(): void
    {
        $this->flags = [];
        $this->into = '';
        $this->rows = [];
    }

    /**
     * Builds the SQL statement from all attributes previously set.
     *
     * @throws \Bloatless\Endocore\Components\QueryBuilder\Exception\DatabaseException
     * @return string
     */
    protected function buildStatement(): string
    {
        $this->statementBuilder->addFlags($this->flags);
        $this->statementBuilder->addInto($this->into);
        $this->statementBuilder->addRows($this->rows);
        return $this->statementBuilder->getStatement();
    }
}
