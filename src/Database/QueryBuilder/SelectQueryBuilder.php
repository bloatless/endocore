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
    public function cols(array $cols = ['*'])
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
    public function from(string $table)
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
    public function where(string $key, string $operator, $value)
    {
        array_push($this->where, [
            'key' => $key,
            'operator' => $operator,
            'value' => $value
        ]);
        return $this;
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
