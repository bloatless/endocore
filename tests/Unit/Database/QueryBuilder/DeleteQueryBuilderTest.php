<?php

namespace Bloatless\Endocore\Tests\Unit\Database\QueryBuilder;

use Bloatless\Endocore\Components\Database\Factory;
use Bloatless\Endocore\Components\Database\QueryBuilder\DeleteQueryBuilder;
use Bloatless\Endocore\Tests\Unit\Database\DatabaseTest;

class DeleteQueryBuilderTest extends DatabaseTest
{
    /**
     * @var array $config
     */
    public $config;

    /**
     * @var Factory $factory
     */
    public $factory;

    public function setUp(): void
    {
        parent::setUp();
        $config = include SC_TESTS . '/Fixtures/config.php';
        $this->config = $config['db'];
        $this->factory = new Factory($this->config);
    }

    public function testFrom()
    {
        $queryBuilder = $this->factory->makeDelete();
        $this->assertInstanceOf(DeleteQueryBuilder::class, $queryBuilder->from('customers'));
    }

    public function testDelete()
    {
        $queryBuilder = $this->factory->makeDelete();
        $affectedRows = $queryBuilder->from('customers')
            ->whereEquals('customer_id', 4)
            ->delete();
        $this->assertEquals(1, $affectedRows);
        $this->assertEquals(3, $this->getRowCount('customers'));
    }

    public function testReset()
    {
        $builder = $this->factory->makeDelete()
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
