<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Database\QueryBuilder;

use Nekudo\ShinyCore\Exception\Application\DatabaseException;

class SelectBuilder extends AbstractBuilder
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

    protected $bindingValues = [];

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
        $pdoStatement = $this->prepare();
        return $pdoStatement->fetchAll(\PDO::FETCH_OBJ);
    }

    public function buildStatement(): string
    {
        $statement = 'SELECT';
        $statement .= $this->addFlags($this->flags);
        $statement .= $this->addCols($this->cols);
        $statement .= $this->addFrom($this->from);
        $statement .= $this->addJoin($this->join);
        $statement .= $this->addWhere($this->where);
        $statement .= $this->addGroupBy($this->groupBy);
        $statement .= $this->addHaving($this->having);
        $statement .= $this->addOrderBy($this->orderBy);
        $statement .= $this->addLimitOffset($this->limit, $this->offset);

        return $statement;
    }

    public function addFlags(array $flags): string
    {
        return '';
    }

    public function addCols(array $cols): string
    {
        $addition = ' ';
        $addition .= implode(', ', $cols);
        $addition .= PHP_EOL;
        return $addition;
    }

    public function addFrom(string $from): string
    {
        $addition = ' FROM ' . $from . PHP_EOL;
        return $addition;
    }

    public function addJoin(array $join): string
    {
        return '';
    }

    public function addWhere(array $where): string
    {
        if (empty($where)) {
            return '';
        }

        $conditions = [];
        foreach ($where as $condition) {
            $this->bindingValues[$condition['key']] = $condition['value'];
            array_push($conditions, sprintf('%s %s :%s', $condition['key'], $condition['operator'], $condition['key']));
        }
        $addition = ' WHERE ';
        $addition .= implode(PHP_EOL . 'AND ', $conditions);

        return $addition;
    }

    public function addGroupBy(array $groupBy): string
    {
        return '';
    }

    public function addHaving(array $having): string
    {
        return '';
    }

    public function addOrderBy(array $orderBy): string
    {
        return '';
    }

    public function addLimitOffset(int $limit, int $offset): string
    {
        return '';
    }

    public function getBindingValues(): array
    {
        return $this->bindingValues;
    }

    protected function prepare(): \PDOStatement
    {
        $statement = $this->buildStatement();
        $params = $this->getBindingValues();

        $pdoStatement = $this->connection->prepare($statement);
        var_dump($pdoStatement);
        foreach ($params as $key => $value) {
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
