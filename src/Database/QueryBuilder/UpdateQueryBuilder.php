<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\QueryBuilder;

/**
 * @property \Nekudo\ShinyCore\Database\StatementBuilder\UpdateStatementBuilder $statementBuilder
 */
class UpdateQueryBuilder extends WhereQueryBuilder
{
    /**
     * @var string $table
     */
    protected $table;

    /**
     * @var array $cols
     */
    protected $cols = [];

    /**
     * Sets table to update.
     *
     * @param string $table
     * @return UpdateQueryBuilder
     */
    public function table(string $table): UpdateQueryBuilder
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Sets columns/values to update and executes statement. Returns number of affected rows.
     *
     * @param array $cols
     * @throws \Nekudo\ShinyCore\Exception\Application\DatabaseException
     * @return int
     */
    public function update(array $cols): int
    {
        $this->cols = $cols;
        $pdoStatement = $this->provideStatement();
        $pdoStatement = $this->execute($pdoStatement);
        return $pdoStatement->rowCount();
    }

    /**
     * Builds the UPDATE statement from all attributes previously set.
     *
     * @return string
     */
    protected function buildStatement(): string
    {
        $this->statementBuilder->addTable($this->table);
        $this->statementBuilder->addCols($this->cols);
        $this->statementBuilder->addWhere($this->where);
        return $this->statementBuilder->getStatement();
    }
}
