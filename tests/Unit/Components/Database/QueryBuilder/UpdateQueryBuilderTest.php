<?php

namespace Bloatless\Endocore\Tests\Unit\Components\Database\QueryBuilder;

use Bloatless\Endocore\Components\Database\Database;
use Bloatless\Endocore\Components\Database\DatabaseFactory;
use Bloatless\Endocore\Components\Database\QueryBuilder\UpdateQueryBuilder;
use Bloatless\Endocore\Tests\Unit\Components\Database\AbstractDatabaseTest;

class UpdateQueryBuilderTest extends AbstractDatabaseTest
{
    private Database $db;

    public function setUp(): void
    {
        parent::setUp();
        $config = include TESTS_ROOT . '/Fixtures/config/config.php';
        $factory = new DatabaseFactory($config);
        $this->db = $factory->make();
    }

    public function testTable()
    {
        $builder = $this->db->makeUpdate();
        $this->assertInstanceOf(UpdateQueryBuilder::class, $builder->table('customers'));
    }

    public function testUpdate()
    {
        $builder = $this->db->makeUpdate();
        $rowsAffected = $builder->table('customers')
            ->whereEquals('firstname', 'Homer')
            ->update([
               'firstname' => 'Max',
            ]);
        $this->assertEquals(1, $rowsAffected);
    }

    public function testReset()
    {
        $builder = $this->db->makeUpdate()
            ->table('foobar')
            ->whereEquals('customer_id', 1);
        $builder->reset();

        $affectedRows = $builder->table('customers')
            ->whereEquals('customer_id', 1)
            ->update(['firstname' => 'Max']);
        $this->assertEquals(1, $affectedRows);
    }
}
