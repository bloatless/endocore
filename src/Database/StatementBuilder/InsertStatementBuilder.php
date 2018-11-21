<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\StatementBuilder;

class InsertStatementBuilder extends StatementBuilder
{
    public function __construct()
    {
        $this->statement = 'INSERT';
    }

    public function addInto(string $table): void
    {

    }

    public function addRows(array $rows): void
    {

    }
}
