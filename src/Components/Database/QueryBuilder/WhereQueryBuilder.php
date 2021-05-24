<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Components\Database\QueryBuilder;

abstract class WhereQueryBuilder extends QueryBuilder
{
    /**
     * @var array $where
     */
    protected $where = [];

    /**
     * Adds a where condition.
     *
     * @param string $key
     * @param string $operator
     * @param mixed $value
     * @return QueryBuilder
     */
    public function where(string $key, string $operator, $value): QueryBuilder
    {
        $this->addWhere($key, $operator, $value, 'AND');
        return $this;
    }

    /**
     * Adds "where equals" condition. (Shortcut for where method with '=' operator).
     *
     * @param string $key
     * @param mixed $value
     * @return QueryBuilder
     */
    public function whereEquals(string $key, $value): QueryBuilder
    {
        return $this->where($key, '=', $value);
    }

    /**
     * Adds a "or where" condition.
     *
     * @param string $key
     * @param string $operator
     * @param mixed $value
     * @return QueryBuilder
     */
    public function orWhere(string $key, string $operator, $value): QueryBuilder
    {
        $this->addWhere($key, $operator, $value, 'OR');
        return $this;
    }

    /**
     * Adds a "where in" condition.
     *
     * @param string $key
     * @param array $values
     * @return QueryBuilder
     */
    public function whereIn(string $key, array $values): QueryBuilder
    {
        $this->addWhere($key, 'IN', $values, 'AND');
        return $this;
    }

    /**
     * Adds a "where not in" condition.
     *
     * @param string $key
     * @param array $values
     * @return QueryBuilder
     */
    public function whereNotIn(string $key, array $values): QueryBuilder
    {
        $this->addWhere($key, 'NOT IN', $values, 'AND');
        return $this;
    }

    /**
     * Adds a "or where not in" condition.
     *
     * @param string $key
     * @param array $values
     * @return QueryBuilder
     */
    public function orWhereIn(string $key, array $values): QueryBuilder
    {
        $this->addWhere($key, 'IN', $values, 'OR');
        return $this;
    }

    /**
     * Adds a "or where not in" condition.
     *
     * @param string $key
     * @param array $values
     * @return QueryBuilder
     */
    public function orWhereNotIn(string $key, array $values): QueryBuilder
    {
        $this->addWhere($key, 'NOT IN', $values, 'OR');
        return $this;
    }

    /**
     * Adds a "where between" condition.
     *
     * @param string $key
     * @param int $min
     * @param int $max
     * @return QueryBuilder
     */
    public function whereBetween(string $key, int $min, int $max): QueryBuilder
    {
        $values = ['min' => $min, 'max' => $max];
        $this->addWhere($key, 'BETWEEN', $values, 'AND');
        return$this;
    }

    /**
     * Adds a "or where between" condition.
     *
     * @param string $key
     * @param int $min
     * @param int $max
     * @return QueryBuilder
     */
    public function orWhereBetween(string $key, int $min, int $max): QueryBuilder
    {
        $values = ['min' => $min, 'max' => $max];
        $this->addWhere($key, 'BETWEEN', $values, 'OR');
        return$this;
    }

    /**
     * Adds a "where null" condition.
     *
     * @param string $key
     * @return QueryBuilder
     */
    public function whereNull(string $key): QueryBuilder
    {
        $this->addWhere($key, 'NULL', [], 'AND');
        return $this;
    }

    /**
     * Adds a "where not null" condition.
     *
     * @param string $key
     * @return QueryBuilder
     */
    public function whereNotNull(string $key): QueryBuilder
    {
        $this->addWhere($key, 'NOT NULL', [], 'AND');
        return $this;
    }

    /**
     * Adds a "or where null" condition.
     *
     * @param string $key
     * @return QueryBuilder
     */
    public function orWhereNull(string $key): QueryBuilder
    {
        $this->addWhere($key, 'NULL', [], 'OR');
        return $this;
    }

    /**
     * Adds a "or where not null" condition.
     *
     * @param string $key
     * @return QueryBuilder
     */
    public function orWhereNotNull(string $key): QueryBuilder
    {
        $this->addWhere($key, 'NOT NULL', [], 'OR');
        return $this;
    }

    /**
     * Adds a raw where clause.
     *
     * @param string $clause
     * @param array $bindings
     * @return QueryBuilder
     */
    public function whereRaw(string $clause, array $bindings = []): QueryBuilder
    {
        $this->addWhere($clause, '', $bindings, 'AND', true);
        return $this;
    }

    /**
     * Adds a raw "or where" clause.
     *
     * @param string $clause
     * @param array $bindings
     * @return QueryBuilder
     */
    public function orWhereRaw(string $clause, array $bindings = []): QueryBuilder
    {
        $this->addWhere($clause, '', $bindings, 'OR', true);
        return $this;
    }

    /**
     * Adds where condition to pool.
     *
     * @param string $key
     * @param string $operator
     * @param $value
     * @param string $concatenator
     * @param bool $raw
     */
    protected function addWhere(
        string $key,
        string $operator,
        $value,
        string $concatenator = 'AND',
        bool $raw = false
    ): void {
        array_push($this->where, [
            'key' => $key,
            'operator' => $operator,
            'value' => $value,
            'concatenator' => $concatenator,
            'raw' => $raw,
        ]);
    }
}
