<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\StatementBuilder;

class SelectStatementBuilder extends StatementBuilder
{
    public function __construct()
    {
        $this->statement = 'SELECT';
    }

    /**
     * Adds possible flags to SQL statement.
     *
     * @param array $flags
     * @return void
     */
    public function addFlags(array $flags): void
    {
    }

    /**
     * Adds fields/columns to SQL statement.
     *
     * @param array $cols
     * @return void
     */
    public function addCols(array $cols): void
    {
        if (empty($cols)) {
            return;
        }

        $this->statement .= ' ';
        $this->statement .= implode(', ', $cols);
        $this->statement .= PHP_EOL;
    }

    /**
     * Adds from-table to SQL statemtent.
     *
     * @param string $from
     * @return void
     */
    public function addFrom(string $from): void
    {
        $this->statement .= ' FROM ' . $from . PHP_EOL;
    }

    /**
     * Adds joins to SQL statement.
     *
     * @param array $join
     * @return void
     */
    public function addJoin(array $join): void
    {
    }

    /**
     * Adds where clause(s) to SQL statement.
     *
     * @param array $where
     * @return void
     */
    public function addWhere(array $where): void
    {
        if (empty($where)) {
            return;
        }

        $conditions = [];
        foreach ($where as $condition) {
            $placeholder = $this->addBindingValue($condition['key'], $condition['value']);
            array_push($conditions, sprintf('%s %s :%s', $condition['key'], $condition['operator'], $placeholder));
        }
        $this->statement .= ' WHERE ';
        $this->statement .= implode(PHP_EOL . 'AND ', $conditions);
    }

    /**
     * Adds "group by clause(s)" to SQL statement.
     *
     * @param array $groupBy
     * @return void
     */
    public function addGroupBy(array $groupBy): void
    {
    }

    /**
     * Adds "having clause(s)" to SQL statement.
     *
     * @param array $having
     * @return void
     */
    public function addHaving(array $having): void
    {
    }

    /**
     * Adds "order by clause(s) to SQL statement.
     *
     * @param array $orderBy
     * @return void
     */
    public function addOrderBy(array $orderBy): void
    {
    }

    /**
     * Adds limit and offset to SQL statement.
     *
     * @param int $limit
     * @param int $offset
     * @return void
     */
    public function addLimitOffset(int $limit, int $offset): void
    {
    }
}
