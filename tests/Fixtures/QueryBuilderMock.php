<?php

namespace Bloatless\Endocore\Components\QueryBuilder\Tests\Fixtures;

use Bloatless\Endocore\Components\QueryBuilder\QueryBuilder\QueryBuilder;

class QueryBuilderMock extends QueryBuilder
{
    protected $testStatement = '';

    public function setTestStatement(string $statement, array $values): void
    {
        $this->testStatement = $statement;
        foreach ($values as $key => $value) {
            $this->statementBuilder->addBindingValue($key, $value);
        }
    }

    public function exposedProvideStatement(): \PDOStatement
    {
        return $this->provideStatement();
    }

    public function exposedPrepareStatement(string $statement, array $values): \PDOStatement
    {
        return $this->prepareStatement($statement, $values);
    }

    public function reset(): void
    {
        // just a mock
    }

    protected function buildStatement(): string
    {
        return $this->testStatement;
    }
}
