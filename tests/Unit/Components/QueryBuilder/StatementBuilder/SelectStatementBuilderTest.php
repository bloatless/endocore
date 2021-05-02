<?php

namespace Bloatless\Endocore\Tests\Unit\Components\QueryBuilder\StatementBuilder;

use Bloatless\Endocore\Components\Database\StatementBuilder\SelectStatementBuilder;
use PHPUnit\Framework\TestCase;

class SelectStatementBuilderTest extends TestCase
{
    public function testInitialization()
    {
        $builder = new SelectStatementBuilder;
        $this->assertEquals('SELECT', $builder->getStatement());
    }

    public function testAddFlags()
    {
        $builder = new SelectStatementBuilder;
        $builder->addFlags([
            'distinct' => true,
            'count' => true
        ]);
        $this->assertEquals('SELECT DISTINCT', $builder->getStatement());
    }

    public function testAddColsWithEmptyCols()
    {
        $builder = new SelectStatementBuilder;
        $builder->addCols([]);
        $this->assertEquals('SELECT', $builder->getStatement());
    }

    public function testAddColsWithOneColumn()
    {
        $builder = new SelectStatementBuilder;
        $builder->addCols(['firstname']);
        $this->assertEquals('SELECT `firstname`' . PHP_EOL, $builder->getStatement());
    }

    public function testAddColsWithMultipleColumns()
    {
        $builder = new SelectStatementBuilder;
        $builder->addCols(['foo', 'bar']);
        $this->assertEquals('SELECT `foo`, `bar`' . PHP_EOL, $builder->getStatement());
    }

    public function testAddColsWithCountFlag()
    {
        $builder = new SelectStatementBuilder;
        $builder->addFlags(['count' => true]);
        $builder->addCols([]);
        $this->assertEquals('SELECT COUNT(*)' . PHP_EOL, $builder->getStatement());
    }

    public function testAddFrom()
    {
        $builder = new SelectStatementBuilder;
        $builder->addFrom('customers');
        $this->assertEquals('SELECT FROM `customers`' . PHP_EOL, $builder->getStatement());
    }

    public function testAddJoin()
    {
        $builder = new SelectStatementBuilder;
        $builder->addJoin([[
            'type' => 'INNER',
            'table' => 'foo',
            'key' => 'a',
            'operator' => '=',
            'value' => 'b'
        ]]);
        $this->assertEquals('SELECTINNER JOIN `foo` ON `a` = `b`' . PHP_EOL, $builder->getStatement());
    }

    public function testAddJoinWithEmptyJoin()
    {
        $builder = new SelectStatementBuilder;
        $builder->addJoin([]);
        $this->assertEquals('SELECT', $builder->getStatement());
    }

    public function testAddGroupBy()
    {
        $builder = new SelectStatementBuilder;
        $builder->addGroupBy(['customers']);
        $this->assertEquals('SELECT GROUP BY `customers`' . PHP_EOL, $builder->getStatement());
    }

    public function testAddGroupByWitMultipleValues()
    {
        $builder = new SelectStatementBuilder;
        $builder->addGroupBy(['foo', 'bar']);
        $this->assertEquals('SELECT GROUP BY `foo`, `bar`' . PHP_EOL, $builder->getStatement());
    }

    public function testAddGroupByWithEmptyValue()
    {
        $builder = new SelectStatementBuilder;
        $builder->addGroupBy([]);
        $this->assertEquals('SELECT', $builder->getStatement());
    }

    public function testAddHaving()
    {
        $builder = new SelectStatementBuilder;
        $builder->addHaving([[
            'concatenator' => 'AND',
            'key' => 'foo',
            'value' => 'bar',
            'operator' => '>'
        ]]);
        $this->assertEquals('SELECT HAVING `foo` > :foo' . PHP_EOL, $builder->getStatement());
    }

    public function testAddHavingWithMultipleValues()
    {
        $builder = new SelectStatementBuilder;
        $builder->addHaving([
            [
                'concatenator' => 'AND',
                'key' => 'a',
                'value' => 1,
                'operator' => '>'
            ],
            [
                'concatenator' => 'OR',
                'key' => 'b',
                'value' => 2,
                'operator' => '<'
            ]
        ]);
        $this->assertEquals('SELECT HAVING `a` > :a' . PHP_EOL . ' OR `b` < :b' . PHP_EOL, $builder->getStatement());
    }

    public function testAddHavingWithEmptyValue()
    {
        $builder = new SelectStatementBuilder;
        $builder->addHaving([]);
        $this->assertEquals('SELECT', $builder->getStatement());
    }

    public function testAddOrderBy()
    {
        $builder = new SelectStatementBuilder;
        $builder->addOrderBy([[
            'key' => 'foo',
            'direction' => 'ASC',
        ]]);
        $this->assertEquals('SELECT ORDER BY `foo` ASC' . PHP_EOL, $builder->getStatement());
    }

    public function testAddOrderByWithMultipleValues()
    {
        $builder = new SelectStatementBuilder;
        $builder->addOrderBy([
            [
                'key' => 'foo',
                'direction' => 'ASC',
            ],
            [
                'key' => 'bar',
                'direction' => 'DESC',
            ],
        ]);
        $this->assertEquals('SELECT ORDER BY `foo` ASC, `bar` DESC' . PHP_EOL, $builder->getStatement());
    }

    public function testAddOrderByWithEmptyValue()
    {
        $builder = new SelectStatementBuilder;
        $builder->addOrderBy([]);
        $this->assertEquals('SELECT', $builder->getStatement());
    }

    public function testAddLimitOffset()
    {
        $builder = new SelectStatementBuilder;
        $builder->addLimitOffset(5, 10);
        $this->assertEquals('SELECT LIMIT 10, 5' . PHP_EOL, $builder->getStatement());
    }

    public function testAddLimitOffsetWithLimitOnly()
    {
        $builder = new SelectStatementBuilder;
        $builder->addLimitOffset(5, 0);
        $this->assertEquals('SELECT LIMIT 5' . PHP_EOL, $builder->getStatement());
    }

    public function testAddLimitOffsetWithOffsetOnly()
    {
        $builder = new SelectStatementBuilder;
        $builder->addLimitOffset(0, 5);
        $this->assertEquals('SELECT', $builder->getStatement());
    }

    public function testAddLimitOffsetWithZeroValues()
    {
        $builder = new SelectStatementBuilder;
        $builder->addLimitOffset(0, 0);
        $this->assertEquals('SELECT', $builder->getStatement());
    }
}
