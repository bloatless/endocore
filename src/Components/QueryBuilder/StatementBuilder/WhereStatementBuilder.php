<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\QueryBuilder\StatementBuilder;

abstract class WhereStatementBuilder extends StatementBuilder
{
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
                    $this->addWhereIn($clause['key'], $clause['value'], false);
                    break;
                case 'NOT IN':
                    $this->addWhereIn($clause['key'], $clause['value'], true);
                    break;
                case 'BETWEEN':
                    $this->addWhereBetween($clause['key'], $clause['value']['min'], $clause['value']['max']);
                    break;
                case 'NULL':
                    $this->addWhereNull($clause['key'], false);
                    break;
                case 'NOT NULL':
                    $this->addWhereNull($clause['key'], true);
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
        $key = $this->quoteName($key);
        $this->statement .= sprintf('%s %s %s', $key, $operator, $placeholder);
        $this->statement .= PHP_EOL;
    }

    /**
     * Adds a "where (not) in" clause to the statement.
     *
     * @param string $key
     * @param array $values
     * @param bool $not
     * @return void
     */
    protected function addWhereIn(string $key, array $values, bool $not = false): void
    {
        $placeholders = [];
        foreach ($values as $value) {
            $placeholder = $this->addBindingValue($key, $value);
            array_push($placeholders, $placeholder);
        }
        $placeholdersList = implode(',', $placeholders);
        $pattern = ($not === true) ? '%s NOT IN (%s)' : '%s IN (%s)';

        $key = $this->quoteName($key);
        $this->statement .= sprintf($pattern, $key, $placeholdersList);
        $this->statement .= PHP_EOL;
    }

    /**
     * Adds a "where between" clause to statement.
     *
     * @param string $key
     * @param int $min
     * @param int $max
     * @return void
     */
    protected function addWhereBetween(string $key, int $min, int $max): void
    {
        $phMin = $this->addBindingValue($key, $min);
        $phMax = $this->addBindingValue($key, $max);
        $key = $this->quoteName($key);
        $this->statement .= sprintf('%s BETWEEN %s AND %s', $key, $phMin, $phMax);
        $this->statement .= PHP_EOL;
    }

    /**
     * Adds "where (not) null" clause to statement.
     *
     * @param string $key
     * @param bool $not
     * @return void
     */
    protected function addWhereNull(string $key, bool $not = false): void
    {
        $key = $this->quoteName($key);
        $pattern = ($not === true) ? '%s IS NOT NULL': '%s IS NULL';
        $this->statement .= sprintf($pattern, $key);
        $this->statement .= PHP_EOL;
    }
}
