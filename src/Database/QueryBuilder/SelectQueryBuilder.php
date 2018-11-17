<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\QueryBuilder;

/**
 * @property \Nekudo\ShinyCore\Database\StatementBuilder\SelectStatementBuilder $statementBuilder
 */
class SelectQueryBuilder extends QueryBuilder
{
    /**
     * @var array $flags
     */
    protected $flags = [];

    /**
     * @var array $cols
     */
    protected $cols = ['*'];

    /**
     * @var string $from
     */
    protected $from = '';

    /**
     * @var array $join
     */
    protected $join = [];

    /**
     * @var array $where
     */
    protected $where = [];

    /**
     * @var array $groupBy
     */
    protected $groupBy = [];

    /**
     * @var array $orderBy
     */
    protected $orderBy = [];

    /**
     * @var array $having
     */
    protected $having = [];

    /**
     * @var int $limit
     */
    protected $limit = 0;

    /**
     * @var int $offset
     */
    protected $offset = 0;

    /**
     * Sets the "distinct" flag.
     *
     * @return SelectQueryBuilder
     */
    public function distinct(): SelectQueryBuilder
    {
        $this->flags['distinct'] = true;
        return $this;
    }

    /**
     * Add fields to select from table(s).
     *
     * @param array $cols
     * @return SelectQueryBuilder
     */
    public function cols(array $cols = ['*']): SelectQueryBuilder
    {
        $this->cols = $cols;
        return $this;
    }

    /**
     * Sets base table to select from.
     *
     * @param string $table
     * @return SelectQueryBuilder
     */
    public function from(string $table): SelectQueryBuilder
    {
        $this->from = $table;
        return $this;
    }

    /**
     * Adds an "inner join".
     *
     * @param string $table
     * @param string $key
     * @param string $operator
     * @param string $value
     * @return SelectQueryBuilder
     */
    public function join(string $table, string $key, string $operator, string $value): SelectQueryBuilder
    {
        $this->addJoin($table, $key, $operator, $value, 'inner');
        return $this;
    }

    /**
     * Adds an "left join".
     *
     * @param string $table
     * @param string $key
     * @param string $operator
     * @param string $value
     * @return SelectQueryBuilder
     */
    public function leftJoin(string $table, string $key, string $operator, string $value): SelectQueryBuilder
    {
        $this->addJoin($table, $key, $operator, $value, 'left');
        return $this;
    }
    /**
     * Adds an "right join".
     *
     * @param string $table
     * @param string $key
     * @param string $operator
     * @param string $value
     * @return SelectQueryBuilder
     */
    public function rightJoin(string $table, string $key, string $operator, string $value): SelectQueryBuilder
    {
        $this->addJoin($table, $key, $operator, $value, 'right');
        return $this;
    }

    /**
     * Adds a where condition.

     * @param string $key
     * @param string $operator
     * @param mixed $value
     * @return SelectQueryBuilder
     */
    public function where(string $key, string $operator, $value): SelectQueryBuilder
    {
        $this->addWhere($key, $operator, $value, 'AND');
        return $this;
    }

    /**
     * Adds "where equals" condition. (Shortcut for where method with '=' operator).
     *
     * @param string $key
     * @param mixed $value
     * @return SelectQueryBuilder
     */
    public function whereEquals(string $key, $value): SelectQueryBuilder
    {
        return $this->where($key, '=', $value);
    }

    /**
     * Adds a "or where" condition.
     *
     * @param string $key
     * @param string $operator
     * @param mixed $value
     * @return SelectQueryBuilder
     */
    public function orWhere(string $key, string $operator, $value): SelectQueryBuilder
    {
        $this->addWhere($key, $operator, $value, 'OR');
        return $this;
    }

    /**
     * Adds a "where in" condition.
     *
     * @param string $key
     * @param array $values
     * @return SelectQueryBuilder
     */
    public function whereIn(string $key, array $values): SelectQueryBuilder
    {
        $this->addWhere($key, 'IN', $values, 'AND');
        return $this;
    }

    /**
     * Adds a "where not in" condition.
     *
     * @param string $key
     * @param array $values
     * @return SelectQueryBuilder
     */
    public function whereNotIn(string $key, array $values): SelectQueryBuilder
    {
        $this->addWhere($key, 'NOT IN', $values, 'AND');
        return $this;
    }

    /**
     * Adds a "or where not in" condition.
     *
     * @param string $key
     * @param array $values
     * @return SelectQueryBuilder
     */
    public function orWhereIn(string $key, array $values): SelectQueryBuilder
    {
        $this->addWhere($key, 'IN', $values, 'OR');
        return $this;
    }

    /**
     * Adds a "or where not in" condition.
     *
     * @param string $key
     * @param array $values
     * @return SelectQueryBuilder
     */
    public function orWhereNotIn(string $key, array $values): SelectQueryBuilder
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
     * @return SelectQueryBuilder
     */
    public function whereBetween(string $key, int $min, int $max): SelectQueryBuilder
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
     * @return SelectQueryBuilder
     */
    public function orWhereBetween(string $key, int $min, int $max): SelectQueryBuilder
    {
        $values = ['min' => $min, 'max' => $max];
        $this->addWhere($key, 'BETWEEN', $values, 'OR');
        return$this;
    }

    /**
     * Adds a "where null" condition.
     *
     * @param string $key
     * @return SelectQueryBuilder
     */
    public function whereNull(string $key): SelectQueryBuilder
    {
        $this->addWhere($key, 'NULL', [], 'AND');
        return $this;
    }

    /**
     * Adds a "where not null" condition.
     *
     * @param string $key
     * @return SelectQueryBuilder
     */
    public function whereNotNull(string $key): SelectQueryBuilder
    {
        $this->addWhere($key, 'NOT NULL', [], 'AND');
        return $this;
    }

    /**
     * Adds a "or where null" condition.
     *
     * @param string $key
     * @return SelectQueryBuilder
     */
    public function orWhereNull(string $key): SelectQueryBuilder
    {
        $this->addWhere($key, 'NULL', [], 'OR');
        return $this;
    }

    /**
     * Adds a "or where not null" condition.
     *
     * @param string $key
     * @return SelectQueryBuilder
     */
    public function orWhereNotNull(string $key): SelectQueryBuilder
    {
        $this->addWhere($key, 'NOT NULL', [], 'OR');
        return $this;
    }

    /**
     * Adds a field to "group by".
     *
     * @param string $key
     * @return SelectQueryBuilder
     */
    public function groupBy(string $key): SelectQueryBuilder
    {
        array_push($this->groupBy, $key);
        return $this;
    }

    /**
     * Adds an order by clause to query.
     *
     * @param string $key
     * @param string $direction
     * @return SelectQueryBuilder
     */
    public function orderBy(string $key, string $direction = 'ASC'): SelectQueryBuilder
    {
        array_push($this->orderBy, [
            'key' => $key,
            'direction' => strtoupper($direction),
        ]);
        return $this;
    }

    /**
     * Adds where condition to pool.
     *
     * @param string $key
     * @param string $operator
     * @param $value
     * @param string $concatenator
     */
    protected function addWhere(string $key, string $operator, $value, $concatenator = 'AND'): void
    {
        array_push($this->where, [
            'key' => $key,
            'operator' => $operator,
            'value' => $value,
            'concatenator' => $concatenator,
        ]);
    }

    /**
     * Adds new join to the pool.
     *
     * @param string $table
     * @param string $key
     * @param string $operator
     * @param string $value
     * @param string $type
     * @return void
     */
    protected function addJoin(
        string $table,
        string $key,
        string $operator,
        string $value,
        string $type = 'inner'
    ): void {
        array_push($this->join, [
            'table' => $table,
            'key' => $key,
            'operator' => $operator,
            'value' => $value,
            'type' => $type,
        ]);
    }

    /**
     * Executes select query and returns all matching rows as array of objects.
     *
     * @return array
     * @throws \Nekudo\ShinyCore\Exception\Application\DatabaseQueryException
     */
    public function get(): array
    {
        $pdoStatement = $this->provideStatement();
        return $pdoStatement->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Builds the SQL statement from all attributes previously set.
     *
     * @return string
     */
    protected function buildStatement(): string
    {
        $this->statementBuilder->addFlags($this->flags);
        $this->statementBuilder->addCols($this->cols);
        $this->statementBuilder->addFrom($this->from);
        $this->statementBuilder->addJoin($this->join);
        $this->statementBuilder->addWhere($this->where);
        $this->statementBuilder->addGroupBy($this->groupBy);
        $this->statementBuilder->addHaving($this->having);
        $this->statementBuilder->addOrderBy($this->orderBy);
        $this->statementBuilder->addLimitOffset($this->limit, $this->offset);

        return $this->statementBuilder->getStatement();
    }
}
