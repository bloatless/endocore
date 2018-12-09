<?php

namespace Nekudo\ShinyCore\Tests\Unit\Database;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Database\ConnectionAdapter\PdoMysql;
use Nekudo\ShinyCore\Database\Factory;
use Nekudo\ShinyCore\Database\QueryBuilder\DeleteQueryBuilder;
use Nekudo\ShinyCore\Database\QueryBuilder\InsertQueryBuilder;
use Nekudo\ShinyCore\Database\QueryBuilder\RawQueryBuilder;
use Nekudo\ShinyCore\Database\QueryBuilder\SelectQueryBuilder;
use Nekudo\ShinyCore\Database\QueryBuilder\UpdateQueryBuilder;
use Nekudo\ShinyCore\Exception\Application\DatabaseException;

class FactoryTest extends DatabaseTest
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
        $configData = include SC_TESTS . '/Fixtures/config.php';
        $configData['db']['connections']['db1']['driver'] = 'foo';
        $config = (new Config)->fromArray($configData);
        $factory = new Factory($config);
        $this->expectException(DatabaseException::class);
        $factory->provideConnection();
        unset($config, $factory);

        // invalid credentials:
        $configData['db']['connections']['db1']['driver'] = 'mysql';
        $configData['db']['connections']['db1']['username'] = 'foo';
        $config = (new Config)->fromArray($configData);
        $factory = new Factory($config);
        $this->expectException(DatabaseException::class);
        $factory->provideConnection();
    }

    public function testAddGetConnection()
    {
        $factory = new Factory($this->config);
        $this->assertFalse($factory->hasConnection('db1'));

        $adapter = new PdoMysql;
        $connection = $adapter->connect($this->config->getDbConfig('db1'));
        $factory->addConnection('foo', $connection);
        $this->assertTrue($factory->hasConnection('foo'));
        $this->assertInstanceOf(\PDO::class, $factory->getConnection('foo'));

        $this->expectException(DatabaseException::class);
        $factory->getConnection('bar');
    }
}
