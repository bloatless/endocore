<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\QueryBuilder;

use Nekudo\ShinyCore\Exception\Application\DatabaseException;

/**
 * @property \Nekudo\ShinyCore\Database\StatementBuilder\SelectStatementBuilder $statementBuilder
 */
class SelectQueryBuilder extends WhereQueryBuilder
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
     * Adds a having clause.
     *
     * @param string $key
     * @param string $operator
     * @param mixed $value
     * @return SelectQueryBuilder
     */
    public function having(string $key, string $operator, $value): SelectQueryBuilder
    {
        $this->addHaving($key, $operator, $value, 'AND');
        return $this;
    }

    /**
     * Adds a "or having" clause.
     *
     * @param string $key
     * @param string $operator
     * @param mixed $value
     * @return SelectQueryBuilder
     */
    public function orHaving(string $key, string $operator, $value): SelectQueryBuilder
    {
        $this->addHaving($key, $operator, $value, 'OR');
        return $this;
    }

    /**
     * Sets a limit.
     *
     * @param int $limit
     * @return SelectQueryBuilder
     */
    public function limit(int $limit = 0): SelectQueryBuilder
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Sets the offset.
     *
     * @param int $offset
     * @return SelectQueryBuilder
     */
    public function offset(int $offset = 0): SelectQueryBuilder
    {
        $this->offset = $offset;
        return $this;
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
     * Adds "having clause" to the pool.
     *
     * @param string $key
     * @param string $operator
     * @param mixed $value
     * @param string $concatenator
     */
    protected function addHaving(string $key, string $operator, $value, string $concatenator = 'AND'): void
    {
        array_push($this->having, [
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
     * @throws \Nekudo\ShinyCore\Exception\Application\DatabaseException
     */
    public function get(): array
    {
        $pdoStatement = $this->provideStatement();
        $pdoStatement = $this->execute($pdoStatement);
        return $pdoStatement->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * Executes select query and returns first matching row.
     *
     * @return \stdClass|null
     * @throws DatabaseException
     */
    public function first(): ?\stdClass
    {
        $pdoStatement = $this->provideStatement();
        $pdoStatement = $this->execute($pdoStatement);
        $row = $pdoStatement->fetch(\PDO::FETCH_OBJ);
        return (!empty($row)) ? $row : null;
    }

    /**
     * Executes a select query and returns values of a single column. Optionally a custom key column can be specified.
     *
     * @param string $column
     * @param string $keyBy
     * @return array
     * @throws DatabaseException
     */
    public function pluck(string $column, string $keyBy = ''): array
    {
        $pdoStatement = $this->provideStatement();
        $pdoStatement = $this->execute($pdoStatement);
        $rows = $pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($rows)) {
            return [];
        }
        if (!isset($rows[0][$column])) {
            throw new DatabaseException('Column not found in result.');
        }
        if (!empty($keyBy) && !isset($rows[0][$keyBy])) {
            throw new DatabaseException('Column to use as key not found in result.');
        }
        $indexKey = (!empty($keyBy)) ? $keyBy : null;
        return array_column($rows, $column, $indexKey);
    }

    /**
     * Executes select query and returns number of matching rows.
     *
     * @return int
     * @throws DatabaseException
     */
    public function count(): int
    {
        $this->flags['count'] = true;
        $pdoStatement = $this->provideStatement();
        $pdoStatement = $this->execute($pdoStatement);
        return $pdoStatement->fetchColumn();
    }

    /**
     * @inheritdoc
     */
    public function reset(): void
    {
        $this->flags = [];
        $this->cols = [];
        $this->from = '';
        $this->join = [];
        $this->where = [];
        $this->groupBy = [];
        $this->orderBy = [];
        $this->limit = 0;
        $this->offset = 0;
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
