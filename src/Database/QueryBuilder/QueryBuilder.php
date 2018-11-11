<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\QueryBuilder;

use Nekudo\ShinyCore\Database\StatementBuilder\StatementBuilder;

abstract class QueryBuilder
{
    protected $connection;

    protected $statementBuilder;

    public function __construct(\PDO $connection, StatementBuilder $statementBuilder)
    {
        $this->connection = $connection;
        $this->statementBuilder = $statementBuilder;
    }

    public function setStatementBuilder(StatementBuilder $statementBuilder): void
    {
        $this->statementBuilder = $statementBuilder;
    }

    public function getStatementBuilder(): StatementBuilder
    {
        return $this->statementBuilder;
    }
}
