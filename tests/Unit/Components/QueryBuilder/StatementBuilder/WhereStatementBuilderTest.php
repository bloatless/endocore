<?php

namespace Bloatless\Endocore\Tests\Unit\Components\QueryBuilder\StatementBuilder;

use Bloatless\Endocore\Tests\Fixtures\Components\QueryBuilder\WhereStatementBuilderMock;
use PHPUnit\Framework\TestCase;

class WhereStatementBuilderTest extends TestCase
{
    public function testAddWhere()
    {
        $builder = new WhereStatementBuilderMock;
        $builder->addWhere([[
            'key' => 'id',
            'value' => 1,
            'operator' => '=',
            'concatenator' => 'AND',
        ]]);
        $this->assertEquals(' WHERE `id` = :id' . PHP_EOL, $builder->getStatement());
    }

    public function testAddWhereWithConcatenation()
    {
        $builder = new WhereStatementBuilderMock;
        $builder->addWhere([
            [
                'key' => 'id',
                'value' => 1,
                'operator' => '=',
                'concatenator' => 'OR',
            ],
            [
                'key' => 'id',
                'value' => 2,
                'operator' => '<',
                'concatenator' => 'OR',
            ]
        ]);
        $this->assertEquals(' WHERE `id` = :id' . PHP_EOL . ' OR `id` < :id1' . PHP_EOL, $builder->getStatement());
    }

    public function testAddWhereWithEmptyValue()
    {
        $builder = new WhereStatementBuilderMock;
        $builder->addWhere([]);
        $this->assertEquals('', $builder->getStatement());
    }

    public function testAddWhereIn()
    {
        $builder = new WhereStatementBuilderMock;
        $builder->addWhere([
            [
                'key' => 'id',
                'value' => [1, 2],
                'operator' => 'IN',
            ]
        ]);
        $this->assertEquals(' WHERE `id` IN (:id,:id1)' . PHP_EOL, $builder->getStatement());
    }

    public function testAddWhereNotIn()
    {
        $builder = new WhereStatementBuilderMock;
        $builder->addWhere([[
            'key' => 'id',
            'value' => [1, 2],
            'operator' => 'NOT IN',
        ]]);
        $this->assertEquals(' WHERE `id` NOT IN (:id,:id1)'.PHP_EOL, $builder->getStatement());
    }

    public function testAddWhereBetween()
    {
        $builder = new WhereStatementBuilderMock;
        $builder->addWhere([[
            'key' => 'id',
            'value' => [
                'min' => 1,
                'max' => 3,
            ],
            'operator' => 'BETWEEN',
        ]]);
        $this->assertEquals(' WHERE `id` BETWEEN :id AND :id1'.PHP_EOL, $builder->getStatement());
        unset($builder);
    }

    public function testAddWhereNull()
    {
        $builder = new WhereStatementBuilderMock;
        $builder->addWhere([[
            'key' => 'id',
            'operator' => 'NULL',
        ]]);
        $this->assertEquals(' WHERE `id` IS NULL'.PHP_EOL, $builder->getStatement());
    }

    public function testAddWhereNotNull()
    {
        $builder = new WhereStatementBuilderMock;
        $builder->addWhere([[
            'key' => 'id',
            'operator' => 'NOT NULL',
        ]]);
        $this->assertEquals(' WHERE `id` IS NOT NULL'.PHP_EOL, $builder->getStatement());
    }
}
