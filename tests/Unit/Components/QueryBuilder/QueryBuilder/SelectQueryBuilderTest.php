<?php

namespace Bloatless\Endocore\Tests\Unit\Components\QueryBuilder\QueryBuilder;

use Bloatless\Endocore\Components\QueryBuilder\Factory;
use Bloatless\Endocore\Components\QueryBuilder\QueryBuilder\SelectQueryBuilder;
use Bloatless\Endocore\Components\QueryBuilder\Exception\DatabaseException;
use Bloatless\Endocore\Tests\Unit\Components\QueryBuilder\DatabaseTest;

class SelectQueryBuilderTest extends DatabaseTest
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
        $config = include TESTS_ROOT . '/Fixtures/config.php';
        $this->config = $config['db'];
        $this->factory = new Factory($this->config);
    }

    public function testSetter()
    {
        $builder = $this->factory->makeSelect()
            ->distinct()
            ->cols(['firstname', 'lastname'])
            ->from('customers')
            ->join('orders', 'order.customer_id', '=', 'customers.customer_id')
            ->leftJoin('orders', 'order.customer_id', '=', 'customers.customer_id')
            ->rightJoin('orders', 'order.customer_id', '=', 'customers.customer_id')
            ->groupBy('orders.order_id')
            ->orderBy('customers.firstname', 'asc')
            ->having('cnt', '>', 3)
            ->orHaving('cnt', '<', 10)
            ->limit(3)
            ->offset(1);
        $this->assertInstanceOf(SelectQueryBuilder::class, $builder);
    }

    public function testGet()
    {
        $result = $this->factory->makeSelect()
            ->cols(['firstname', 'lastname'])
            ->from('customers')
            ->get();
        $this->assertIsArray($result);
        $this->assertCount(4, $result);
    }

    public function testGetWithEmptyResult()
    {
        $result = $this->factory->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 42)
            ->get();
        $this->assertEquals([], $result);
    }

    public function testFirst()
    {
        // test with result:
        $result = $this->factory->makeSelect()
            ->cols(['firstname', 'lastname'])
            ->from('customers')
            ->first();
        $this->assertInstanceOf(\stdClass::class, $result);
        $this->assertEquals($result->firstname, 'Homer');
    }

    public function testFirstWithEmptyResult()
    {
        $result = $this->factory->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 42)
            ->first();
        $this->assertEquals(null, $result);
    }

    public function testPluckWithColumnOnly()
    {
        $result = $this->factory->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 1)
            ->pluck('firstname');
        $this->assertEquals([0 => 'Homer'], $result);
    }

    public function testPluckWithColumnAndKeyBy()
    {
        $result = $this->factory->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 2)
            ->pluck('firstname', 'customer_id');
        $this->assertEquals([2 => 'Marge'], $result);
    }

    public function testPluckWithEmptyResult()
    {
        $result = $this->factory->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 42)
            ->pluck('firstname', 'customer_id');
        $this->assertEquals([], $result);
    }

    public function testPluckWithInvalidColumn()
    {
        $this->expectException(DatabaseException::class);
        $this->factory->makeSelect()
            ->from('customers')
            ->pluck('foo');
    }

    public function testPluckWithInvalidKeyBy()
    {
        $this->expectException(DatabaseException::class);
        $this->factory->makeSelect()
            ->from('customers')
            ->pluck('firstname', 'foo');
    }

    public function testCount()
    {
        $count = $this->factory->makeSelect()
            ->from('customers')
            ->count();
        $this->assertEquals(4, $count);
    }

    public function testReset()
    {
        $builder = $this->factory->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 1);
        $builder->reset();

        $result = $builder->from('customers')
            ->whereEquals('customer_id', 2)
            ->first();
        $this->assertIsObject($result);
        $this->assertEquals('Marge', $result->firstname);
    }
}
