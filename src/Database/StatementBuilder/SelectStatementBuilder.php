<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\StatementBuilder;

class SelectStatementBuilder extends StatementBuilder
{
    public function __construct()
    {
        $this->statement = 'SELECT';
    }

    public function addFlags(array $flags): string
    {
        return '';
    }

    public function addCols(array $cols): void
    {
        if (empty($cols)) {
            return;
        }

        $this->statement .= ' ';
        $this->statement .= implode(', ', $cols);
        $this->statement .= PHP_EOL;
    }

    public function addFrom(string $from): void
    {
        $this->statement .= ' FROM ' . $from . PHP_EOL;
    }

    public function addJoin(array $join): void
    {
    }

    public function addWhere(array $where): void
    {
        if (empty($where)) {
            return;
        }

        $conditions = [];
        foreach ($where as $condition) {
            $this->bindingValues[$condition['key']] = $condition['value'];
            array_push($conditions, sprintf('%s %s :%s', $condition['key'], $condition['operator'], $condition['key']));
        }
        $this->statement .= ' WHERE ';
        $this->statement .= implode(PHP_EOL . 'AND ', $conditions);

    }

    public function addGroupBy(array $groupBy): void
    {
    }

    public function addHaving(array $having): void
    {
    }

    public function addOrderBy(array $orderBy): void
    {
    }

    public function addLimitOffset(int $limit, int $offset): void
    {
    }
}
