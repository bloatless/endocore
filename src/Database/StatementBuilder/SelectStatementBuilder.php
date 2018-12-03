<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\StatementBuilder;

class SelectStatementBuilder extends WhereStatementBuilder
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
        if (!empty($flags['distinct'])) {
            $this->statement .= ' DISTINCT';
        }
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

        foreach ($cols as $i => $col) {
            $cols[$i] = $this->quoteName($col);
        }

        $this->statement .= ' ';
        $this->statement .= implode(', ', $cols);
        $this->statement .= PHP_EOL;
    }

    /**
     * Adds from-table to SQL statement.
     *
     * @param string $from
     * @return void
     */
    public function addFrom(string $from): void
    {
        $from = $this->quoteName($from);
        $this->statement .= ' FROM ' . $from . PHP_EOL;
    }

    /**
     * Adds joins to SQL statement.
     *
     * @param array $joins
     * @return void
     */
    public function addJoin(array $joins): void
    {
        if (empty($joins)) {
            return;
        }

        foreach ($joins as $join) {
            $keyword = strtoupper($join['type']);
            $pattern = '%s JOIN %s ON %s %s %s';
            $this->statement .= sprintf(
                $pattern,
                $keyword,
                $this->quoteName($join['table']),
                $this->quoteName($join['key']),
                $join['operator'],
                $this->quoteName($join['value'])
            );
            $this->statement .= PHP_EOL;
        }
    }

    /**
     * Adds "group by clause(s)" to SQL statement.
     *
     * @param array $groupBy
     * @return void
     */
    public function addGroupBy(array $groupBy): void
    {
        if (empty($groupBy)) {
            return;
        }
        foreach ($groupBy as $i => $field) {
            $groupBy[$i] = $this->quoteName($field);
        }
        $groupByList = implode(', ', $groupBy);
        $this->statement .= ' GROUP BY ' . $groupByList . PHP_EOL;
    }

    /**
     * Adds "having clause(s)" to SQL statement.
     *
     * @param array $having
     * @return void
     */
    public function addHaving(array $having): void
    {
        if (empty($having)) {
            return;
        }

        $firstClause = true;
        $this->statement .= ' HAVING ';
        foreach ($having as $clause) {
            if ($firstClause === false) {
                $this->statement .= ' ' . $clause['concatenator'] . ' ';
            }
            $placeholder = $this->addBindingValue($clause['key'], $clause['value']);
            $key = $this->quoteName($clause['key']);
            $this->statement .= sprintf('%s %s %s', $key, $clause['operator'], $placeholder);
            $this->statement .= PHP_EOL;
            $firstClause = false;
        }
    }

    /**
     * Adds "order by" clause(s) to SQL statement.
     *
     * @param array $orderBy
     * @return void
     */
    public function addOrderBy(array $orderBy): void
    {
        if (empty($orderBy)) {
            return;
        }
        $orderByList = [];
        foreach ($orderBy as $clause) {
            $clause['key'] = $this->quoteName($clause['key']);
            array_push($orderByList, $clause['key'] . ' ' . $clause['direction']);
        }
        $pattern = ' ORDER BY %s';
        $this->statement .= sprintf($pattern, implode(', ', $orderByList));
        $this->statement .= PHP_EOL;
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
        if ($limit === 0 && $offset === 0) {
            return;
        }
        if ($limit === 0 && $offset <> 0) {
            return;
        }
        if ($offset === 0) {
            $this->statement .= ' LIMIT ' . $limit;
        } else {
            $this->statement .= sprintf(' LIMIT %d, %d', $offset, $limit);
        }
        $this->statement .= PHP_EOL;
    }
}
