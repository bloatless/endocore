<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\StatementBuilder;

class DeleteStatementBuilder extends WhereStatementBuilder
{
    public function __construct()
    {
        $this->statement = 'DELETE';
    }

    /**
     * Adds table to delete from to statement.
     *
     * @param string $from
     */
    public function addFrom(string $from): void
    {
        $this->statement .= ' FROM ' . $this->quoteName($from) . PHP_EOL;
    }
}
