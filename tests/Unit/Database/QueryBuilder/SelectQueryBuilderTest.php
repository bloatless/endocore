<?php

namespace Nekudo\ShinyCore\Tests\Unit\Database\QueryBuilder;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Database\Factory;
use Nekudo\ShinyCore\Database\QueryBuilder\SelectQueryBuilder;
use Nekudo\ShinyCore\Exception\Application\DatabaseException;
use Nekudo\ShinyCore\Tests\Unit\Database\DatabaseTest;

class SelectQueryBuilderTest extends DatabaseTest
{
    /**
     * @var Config $config
     */
    public $config;

    /**
     * @var Factory $factory
     */
    public $factory;

    public function setUp(): void
    {
        parent::setUp();
        $config = include SC_TESTS . '/Mocks/config.php';
        $this->config = (new Config)->fromArray($config);
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
        // test with result:
        $result = $this->factory->makeSelect()
            ->cols(['firstname', 'lastname'])
            ->from('customers')
            ->get();
        $this->assertInternalType('array', $result);
        $this->assertCount(4, $result);

        // test empty result:
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

        // test empty result:
        $result = $this->factory->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 42)
            ->first();
        $this->assertEquals(null, $result);
    }

    public function testPluck()
    {
        // test with valid column:
        $result = $this->factory->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 1)
            ->pluck('firstname');
        $this->assertEquals([0 => 'Homer'], $result);

        // test with valid keyBy:
        $result = $this->factory->makeSelect()
            ->from('customers')
            ->whereEquals('customer_id', 2)
            ->pluck('firstname', 'customer_id');
        $this->assertEquals([2 => 'Marge'], $result);

        // test empty result:
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
}
