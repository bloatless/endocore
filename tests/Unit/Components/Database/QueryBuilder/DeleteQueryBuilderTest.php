<?php

namespace Bloatless\Endocore\Tests\Unit\Components\Database\QueryBuilder;

use Bloatless\Endocore\Components\Database\Database;
use Bloatless\Endocore\Components\Database\DatabaseFactory;
use Bloatless\Endocore\Components\Database\QueryBuilder\DeleteQueryBuilder;
use Bloatless\Endocore\Tests\Unit\Components\Database\AbstractDatabaseTest;

class DeleteQueryBuilderTest extends AbstractDatabaseTest
{
    private Database $db;

    public function setUp(): void
    {
        parent::setUp();
        $config = include TESTS_ROOT . '/Fixtures/config.php';
        $factory = new DatabaseFactory($config);
        $this->db = $factory->make();
    }

    public function testFrom()
    {
        $queryBuilder = $this->db->makeDelete();
        $this->assertInstanceOf(DeleteQueryBuilder::class, $queryBuilder->from('customers'));
    }

    public function testDelete()
    {
        $queryBuilder = $this->db->makeDelete();
        $affectedRows = $queryBuilder->from('customers')
            ->whereEquals('customer_id', 4)
            ->delete();
        $this->assertEquals(1, $affectedRows);
        $this->assertEquals(3, $this->getRowCount('customers'));
    }

    public function testReset()
    {
        $builder = $this->db->makeDelete()
            ->from('customers')
            ->whereEquals('customer_id', 1);
        $builder->reset();
        $affectedRows = $builder->from('customers')
            ->whereEquals('customer_id', 42)
            ->delete();
        $this->assertEquals(0, $affectedRows);
        $this->assertEquals(4, $this->getRowCount('customers'));
    }
}
