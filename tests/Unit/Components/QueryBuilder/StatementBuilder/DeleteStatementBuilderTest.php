<?php

namespace Bloatless\Endocore\Tests\Unit\Components\QueryBuilder\StatementBuilder;

use Bloatless\Endocore\Components\Database\StatementBuilder\DeleteStatementBuilder;
use PHPUnit\Framework\TestCase;

class DeleteStatementBuilderTest extends TestCase
{
    public function testInitialization()
    {
        $builder = new DeleteStatementBuilder;
        $this->assertEquals('DELETE', $builder->getStatement());
    }

    public function testAddFrom()
    {
        $builder = new DeleteStatementBuilder;
        $builder->addFrom('customers');
        $this->assertEquals("DELETE FROM `customers`" . PHP_EOL, $builder->getStatement());
    }
}
