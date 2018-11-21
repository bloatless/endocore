<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\QueryBuilder;

/**
 * @property \Nekudo\ShinyCore\Database\StatementBuilder\InsertStatementBuilder $statementBuilder
 */
class InsertQueryBuilder extends QueryBuilder
{
    /**
     * @var string $into
     */
    protected $into = '';

    /**
     * @var array $insert
     */
    protected $insert = [];

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

    public function insert(array $data): int
    {
        $this->insert = $data;
        // @todo execute statement and return insert id
    }

    public function insertMultiple(array $data): array
    {
        $this->insert = $data;
        // todo execute statement and return insert ids
    }

    /**
     * Builds the SQL statement from all attributes previously set.
     *
     * @return string
     */
    protected function buildStatement(): string
    {
        $this->statementBuilder->addInto($this->into);
        $this->statementBuilder->addRows($this->insert);
        return $this->statementBuilder->getStatement();
    }
}
