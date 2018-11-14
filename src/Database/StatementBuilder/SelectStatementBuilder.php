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
     * Adds from-table to SQL statement.
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

        $firstClause = true;
        $this->statement .= ' WHERE ';
        foreach ($where as $clause) {
            if ($firstClause === false) {
                $this->statement .= ' ' . $clause['concatenator'] . ' ';
            }
            switch ($clause['operator']) {
                case 'IN':
                    $this->addWhereIn($clause['key'], $clause['value']);
                    break;
                case 'BETWEEN':
                    break;
                default:
                    $this->addSimpleWhere($clause['key'], $clause['operator'], $clause['value']);
                    break;
            }

            $firstClause = false;
        }
    }

    /**
     * Adds a regular where clause to the statement.
     *
     * @param string $key
     * @param string $operator
     * @param mixed $value
     * @return void
     */
    protected function addSimpleWhere(string $key, string $operator, $value): void
    {
        $placeholder = $this->addBindingValue($key, $value);
        $this->statement .= sprintf('%s %s %s', $key, $operator, $placeholder);
        $this->statement .= PHP_EOL;
    }

    /**
     * Adds a "where in" clause to the statement.
     *
     * @param string $key
     * @param array $values
     * @return  void
     */
    protected function addWhereIn(string $key, array $values): void
    {
        $placeholders = [];
        foreach ($values as $value) {
            $placeholder = $this->addBindingValue($key, $value);
            array_push($placeholders, $placeholder);
        }
        $placeholdersList = implode(',', $placeholders);
        $this->statement .= sprintf('%s IN (%s)', $key, $placeholdersList);
        $this->statement .= PHP_EOL;
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