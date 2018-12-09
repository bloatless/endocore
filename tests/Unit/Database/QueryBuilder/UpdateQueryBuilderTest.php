<?php

namespace Nekudo\ShinyCore\Tests\Unit\Database\QueryBuilder;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Database\Factory;
use Nekudo\ShinyCore\Database\QueryBuilder\UpdateQueryBuilder;
use Nekudo\ShinyCore\Tests\Unit\Database\DatabaseTest;

class UpdateQueryBuilderTest extends DatabaseTest
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
        $config = include SC_TESTS . '/Fixtures/config.php';
        $this->config = (new Config)->fromArray($config);
        $this->factory = new Factory($this->config);
    }

    public function testTable()
    {
        $builder = $this->factory->makeUpdate();
        $this->assertInstanceOf(UpdateQueryBuilder::class, $builder->table('customers'));
    }

    public function testUpdate()
    {
        $builder = $this->factory->makeUpdate();
        $rowsAffected = $builder->table('customers')
            ->whereEquals('firstname', 'Homer')
            ->update([
               'firstname' => 'Max',
            ]);
        $this->assertEquals(1, $rowsAffected);
    }

    public function testReset()
    {
        $builder = $this->factory->makeUpdate()
            ->table('foobar')
            ->whereEquals('customer_id', 1);
        $builder->reset();

        $affectedRows = $builder->table('customers')
            ->whereEquals('customer_id', 1)
            ->update(['firstname' => 'Max']);
        $this->assertEquals(1, $affectedRows);
    }
}
