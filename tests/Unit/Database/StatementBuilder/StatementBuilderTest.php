<?php

namespace Nekudo\ShinyCore\Tests\Unit\Database\StatementBuilder;

use Nekudo\ShinyCore\Tests\Mocks\StatementBuilderMock;
use PHPUnit\Framework\TestCase;

class StatementBuilderTest extends TestCase
{
    public function testGetStatement()
    {
        $statementBuilder = new StatementBuilderMock;
        $this->assertEquals('', $statementBuilder->getStatement());

        $statementBuilder->setStatement('foobar');
        $this->assertEquals('foobar', $statementBuilder->getStatement());
    }

    public function testAddGetBindingValues()
    {
        $statementBuilder = new StatementBuilderMock;
        $placeholder = $statementBuilder->addBindingValue('foo', 'bar');
        $this->assertEquals(['foo' => 'bar'], $statementBuilder->getBindingValues());
        $this->assertEquals(':foo', $placeholder);

        $placeholder = $statementBuilder->addBindingValue('foo', 'baz');
        $this->assertEquals(':foo1', $placeholder);

        $placeholder = $statementBuilder->addBindingValue('user.id', 42);
        $this->assertEquals(':id', $placeholder);
    }

    public function testQuoteName()
    {
        $statementBuilder = new StatementBuilderMock;
        $this->assertEquals('`foo`', $statementBuilder->exposedQuoteName('foo'));
        $this->assertEquals('`foo`.`bar`', $statementBuilder->exposedQuoteName('foo.bar'));
        $this->assertEquals('`foo` AS `bar`', $statementBuilder->exposedQuoteName('foo AS bar'));
    }
}
