<?php

declare(strict_types=);

namespace Nekudo\ShinyCore\Database\QueryBuilder;

/**
 * @property \Nekudo\ShinyCore\Database\StatementBuilder\UpdateStatementBuilder $statementBuilder
 */
class UpdateQueryBuilder extends WhereQueryBuilder
{
    /**
     * Builds the UPDATE state from all attributes previously set.
     *
     * @return string
     */
    protected function buildStatement(): string
    {
        return $this->statementBuilder->getStatement();
    }
}
