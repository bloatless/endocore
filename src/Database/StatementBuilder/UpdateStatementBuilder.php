<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\StatementBuilder;

class UpdateStatementBuilder extends WhereStatementBuilder
{
    public function __construct()
    {
        $this->statement = 'UPDATE';
    }
}
