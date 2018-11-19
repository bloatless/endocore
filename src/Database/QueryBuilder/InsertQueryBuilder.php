<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\QueryBuilder;

/**
 * @property \Nekudo\ShinyCore\Database\StatementBuilder\InsertStatementBuilder $statementBuilder
 */
class InsertQueryBuilder extends QueryBuilder
{
    /**
     * Builds the SQL statement from all attributes previously set.
     *
     * @return string
     */
    protected function buildStatement(): string
    {
        return $this->statementBuilder->getStatement();
    }
}
