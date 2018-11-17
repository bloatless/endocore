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
     * Adds "where equals" condition.
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

    public function whereNotIn(string $key, array $values): SelectQueryBuilder
    {
        $this->addWhere($key, 'NOT IN', $values, 'AND');
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
