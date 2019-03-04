<?php

namespace Bloatless\Endocore\Tests\Unit\Database\StatementBuilder;

use Bloatless\Endocore\Components\Database\StatementBuilder\UpdateStatementBuilder;
use PHPUnit\Framework\TestCase;

class UpdateStatementBuilderTest extends TestCase
{
    public function testInitialization()
    {
        $builder = new UpdateStatementBuilder;
        $this->assertEquals('UPDATE', $builder->getStatement());
    }

    public function testAddTable()
    {
        $builder = new UpdateStatementBuilder;
        $builder->addTable('customers');
        $this->assertEquals('UPDATE `customers`'. PHP_EOL, $builder->getStatement());
    }

    public function testAddCols()
    {
        $builder = new UpdateStatementBuilder;
        $builder->addCols(['foo' => 'bar']);
        $this->assertEquals('UPDATESET ' . PHP_EOL . '`foo` = :foo' . PHP_EOL, $builder->getStatement());
    }
}
