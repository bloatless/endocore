<?php

namespace Bloatless\Endocore\Components\QueryBuilder\Tests\Unit\QueryBuilder;

use Bloatless\Endocore\Components\QueryBuilder\Factory;
use Bloatless\Endocore\Components\QueryBuilder\QueryBuilder\RawQueryBuilder;
use Bloatless\Endocore\Components\QueryBuilder\Tests\Unit\DatabaseTest;

class RawQueryBuilderTest extends DatabaseTest
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

    public function testPrepare()
    {
        $builder = $this->factory->makeRaw();
        $this->assertInstanceOf(RawQueryBuilder::class, $builder->prepare('SELECT * FROM `customers`'));
    }

    public function testGet()
    {
        $builder = $this->factory->makeRaw();
        $builder->prepare('SELECT `firstname` FROM `customers` WHERE `customer_id` = :id', ['id' => 1]);
        $result = $builder->get();
        $this->assertIsArray($result);
        $this->assertEquals('Homer', $result[0]->firstname);
    }

    public function testRun()
    {
        $builder = $this->factory->makeRaw();
        $builder->prepare('INSERT INTO `customers` (`firstname`, `lastname`) VALUES (:fn, :ln)', [
           'fn' => 'Maggie',
           'ln' => 'Simpson',
        ])->run();
        $this->assertEquals(5, $this->getRowCount('customers'));
    }

    public function testReset()
    {
        $builder = $this->factory->makeRaw();
        $builder->prepare('SELECT FROM `foo`', ['foo' => 'bar']);
        $builder->reset();
        $res = $builder->prepare('SELECT COUNT(*) AS cnt FROM `customers`')->get();
        $this->assertIsArray($res);
        $this->assertEquals(4, $res[0]->cnt);
    }
}
