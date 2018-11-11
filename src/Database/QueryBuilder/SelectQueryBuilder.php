<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\QueryBuilder;

use Nekudo\ShinyCore\Exception\Application\DatabaseException;

/**
 * @property \Nekudo\ShinyCore\Database\StatementBuilder\SelectStatementBuilder $statementBuilder
 */
class SelectQueryBuilder extends QueryBuilder
{
    protected $flags = [];

    protected $cols = ['*'];

    protected $from = '';

    protected $join = [];

    protected $where = [];

    protected $groupBy = [];

    protected $orderBy = [];

    protected $having = [];

    protected $limit = 0;

    protected $offset = 0;

    public function cols(array $cols = ['*'])
    {
        $this->cols = $cols;
        return $this;
    }

    public function from(string $table)
    {
        $this->from = $table;
        return $this;
    }

    public function where(string $key, string $operator, $value)
    {
        array_push($this->where, [
            'key' => $key,
            'operator' => $operator,
            'value' => $value
        ]);
        return $this;
    }

    public function get()
    {
        $pdoStatement = $this->provideStatement();
        return $pdoStatement->fetchAll(\PDO::FETCH_OBJ);
    }


    protected function provideStatement(): \PDOStatement
    {
        $sqlStatement = $this->buildStatement();
        $bindingValues = $this->statementBuilder->getBindingValues();
        return $this->prepare($sqlStatement, $bindingValues);
    }

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

    /**
     * @todo introduce query exception
     * @return \PDOStatement
     * @throws DatabaseException
     */
    protected function prepare(string $sqlStatement, array $bindingValues): \PDOStatement
    {
        $pdoStatement = $this->connection->prepare($sqlStatement);

        foreach ($bindingValues as $key => $value) {
            if (is_int($value)) {
                $pdoStatement->bindValue($key, $value, \PDO::PARAM_INT);
            } elseif (is_bool($value)) {
                $pdoStatement->bindValue($key, $value, \PDO::PARAM_BOOL);
            } elseif (is_null($value)) {
                $pdoStatement->bindValue($key, $value, \PDO::PARAM_NULL);
            } else {
                $pdoStatement->bindValue($key, $value, \PDO::PARAM_STR);
            }
        }

        $result = $pdoStatement->execute();
        if ($result === false) {
            $pdoError = $pdoStatement->errorInfo();
            $errorMessage = $pdoError[2] ?? 'Unspecified SQL error.';
            throw new DatabaseException($errorMessage);
        }

        return $pdoStatement;
    }
}
