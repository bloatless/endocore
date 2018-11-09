<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database;

class QueryBuilder
{
    protected $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function table(string $tableName): QueryBuilder
    {
        return $this;
    }

    public function where(string $key, string $operator, $value): QueryBuilder
    {
        return $this;
    }

    public function select(array $fields = ['*']): QueryBuilder
    {
        return $this;
    }

    public function get()
    {

    }
}
