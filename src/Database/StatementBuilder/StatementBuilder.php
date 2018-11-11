<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\StatementBuilder;

abstract class StatementBuilder
{
    protected $statement = '';

    protected $bindingValues = [];

    public function getStatement(): string
    {
        return $this->statement;
    }

    public function getBindingValues(): array
    {
        return $this->bindingValues;
    }
}
