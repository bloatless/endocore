<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\QueryBuilder;

abstract class AbstractBuilder
{
    protected $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }
}
