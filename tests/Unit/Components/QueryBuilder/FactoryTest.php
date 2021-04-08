<?php

namespace Bloatless\Endocore\Components\QueryBuilder\Tests\Unit;

use Bloatless\Endocore\Components\QueryBuilder\ConnectionAdapter\PdoMysql;
use Bloatless\Endocore\Components\QueryBuilder\Factory;
use Bloatless\Endocore\Components\QueryBuilder\QueryBuilder\DeleteQueryBuilder;
use Bloatless\Endocore\Components\QueryBuilder\QueryBuilder\InsertQueryBuilder;
use Bloatless\Endocore\Components\QueryBuilder\QueryBuilder\RawQueryBuilder;
use Bloatless\Endocore\Components\QueryBuilder\QueryBuilder\SelectQueryBuilder;
use Bloatless\Endocore\Components\QueryBuilder\QueryBuilder\UpdateQueryBuilder;
use Bloatless\Endocore\Components\QueryBuilder\Exception\DatabaseException;

class FactoryTest extends DatabaseTest
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

    public function testInitWithoutConnections()
    {
        $config = $this->config;
        unset($config['connections']);
        $this->expectException(DatabaseException::class);
        $factory = new Factory($config);
    }

    public function testMakeInsert()
    {
        $this->assertInstanceOf(InsertQueryBuilder::class, $this->factory->makeInsert());
    }

    public function testMakeSelect()
    {
        $this->assertInstanceOf(SelectQueryBuilder::class, $this->factory->makeSelect());
    }

    public function testMakeUpdate()
    {
        $this->assertInstanceOf(UpdateQueryBuilder::class, $this->factory->makeUpdate());
    }

    public function testMakDelete()
    {
        $this->assertInstanceOf(DeleteQueryBuilder::class, $this->factory->makeDelete());
    }

    public function testMakeRaw()
    {
        $this->assertInstanceOf(RawQueryBuilder::class, $this->factory->makeRaw());
    }

    public function testProvideConnection()
    {
        $factory = new Factory($this->config);

        // default conneciton:
        $connection = $factory->provideConnection();
        $this->assertInstanceOf(\PDO::class, $connection);

        // named connection:
        $connection = $factory->provideConnection('db1');
        $this->assertInstanceOf(\PDO::class, $connection);

        unset($factory, $connection);

        // invalid driver:
        $configData = include TESTS_ROOT . '/Fixtures/config.php';
        $configData['db']['connections']['db1']['driver'] = 'foo';
        $factory = new Factory($configData['db']);
        $this->expectException(DatabaseException::class);
        $factory->provideConnection();
        unset($config, $factory);

        // invalid credentials:
        $configData['db']['connections']['db1']['driver'] = 'mysql';
        $configData['db']['connections']['db1']['username'] = 'foo';
        $factory = new Factory($configData['db']);
        $this->expectException(DatabaseException::class);
        $factory->provideConnection();
    }

    public function testAddGetConnection()
    {
        $factory = new Factory($this->config);
        $this->assertFalse($factory->hasConnection('db1'));

        $adapter = new PdoMysql;
        $connection = $adapter->connect($this->config['connections']['db1']);
        $factory->addConnection('foo', $connection);
        $this->assertTrue($factory->hasConnection('foo'));
        $this->assertInstanceOf(\PDO::class, $factory->getConnection('foo'));

        $this->expectException(DatabaseException::class);
        $factory->getConnection('bar');
    }
}
