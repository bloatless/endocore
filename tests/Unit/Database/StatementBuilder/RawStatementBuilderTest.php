<?php

namespace Nekudo\ShinyCore\Tests\Unit\Database\StatementBuilder;

use Nekudo\ShinyCore\Database\StatementBuilder\RawStatementBuider;
use PHPUnit\Framework\TestCase;

class RawStatementBuilderTest extends TestCase
{
    public function testPrepareRawStatement()
    {
        // Without binding:
        $statementBuilder = new RawStatementBuider;
        $statementBuilder->prepareRawStatement("SELECT * FROM foo", []);
        $this->assertEquals('SELECT * FROM foo', $statementBuilder->getStatement());
        unset($statementBuilder);

        // With binding:
        $statementBuilder = new RawStatementBuider;
        $statementBuilder->prepareRawStatement("SELECT * FROM foo WHERE id = :id", [
           'id' => 42
        ]);
        $this->assertEquals("SELECT * FROM foo WHERE id = :id", $statementBuilder->getStatement());
        $this->assertEquals(['id' => 42], $statementBuilder->getBindingValues());
    }
}
