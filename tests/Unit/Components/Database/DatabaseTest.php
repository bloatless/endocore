<?php

namespace Bloatless\Endocore\Tests\Unit\Components\Database;

use Bloatless\Endocore\Components\Database\ConnectionAdapter\PdoMysql;
use Bloatless\Endocore\Components\Database\Database;
use Bloatless\Endocore\Components\Database\Exception\DatabaseException;
use Bloatless\Endocore\Components\Database\QueryBuilder\DeleteQueryBuilder;
use Bloatless\Endocore\Components\Database\QueryBuilder\InsertQueryBuilder;
use Bloatless\Endocore\Components\Database\QueryBuilder\RawQueryBuilder;
use Bloatless\Endocore\Components\Database\QueryBuilder\SelectQueryBuilder;
use Bloatless\Endocore\Components\Database\QueryBuilder\UpdateQueryBuilder;

class DatabaseTest extends AbstractDatabaseTest
{
    public function testMakeInsert()
    {
        $db = $this->provideDatabase();
        $this->assertInstanceOf(InsertQueryBuilder::class, $db->makeInsert());
    }

    public function testMakeSelect()
    {
        $db = $this->provideDatabase();
        $this->assertInstanceOf(SelectQueryBuilder::class, $db->makeSelect());
    }

    public function testMakeUpdate()
    {
        $db = $this->provideDatabase();
        $this->assertInstanceOf(UpdateQueryBuilder::class, $db->makeUpdate());
    }

    public function testMakeDelete()
    {
        $db = $this->provideDatabase();
        $this->assertInstanceOf(DeleteQueryBuilder::class, $db->makeDelete());
    }

    public function testMakeRaw()
    {
        $db = $this->provideDatabase();
        $this->assertInstanceOf(RawQueryBuilder::class, $db->makeRaw());
    }

    public function testProvideConnection()
    {
        $db = $this->provideDatabase();

        // default connection:
        $connection = $db->provideConnection();
        $this->assertInstanceOf(\PDO::class, $connection);

        // named connection:
        $connection = $db->provideConnection('db1');
        $this->assertInstanceOf(\PDO::class, $connection);

        unset($db, $connection);

        // invalid driver:
        $configData = include TESTS_ROOT . '/Fixtures/config/config.php';
        $configData['db']['connections']['db1']['driver'] = 'foo';
        $db = new Database($configData['db']['connections'], 'db1');
        $this->expectException(DatabaseException::class);
        $db->provideConnection();
        unset($config, $db);

        // invalid credentials:
        $configData['db']['connections']['db1']['driver'] = 'mysql';
        $configData['db']['connections']['db1']['username'] = 'foo';
        $db = new Database($configData['db'], 'db1');
        $this->expectException(DatabaseException::class);
        $db->provideConnection();
    }

    public function testAddGetConnection()
    {
        $config = include TESTS_ROOT . '/Fixtures/config/config.php';

        $db = $this->provideDatabase();
        $this->assertFalse($db->hasConnection('db1'));

        $adapter = new PdoMysql;
        $connection = $adapter->connect($config['db']['connections']['db1']);
        $db->addConnection('foo', $connection);
        $this->assertTrue($db->hasConnection('foo'));
        $this->assertInstanceOf(\PDO::class, $db->getConnection('foo'));

        $this->expectException(DatabaseException::class);
        $db->getConnection('bar');
    }

    private function provideDatabase()
    {
        $config = include TESTS_ROOT . '/Fixtures/config/config.php';
        $db = new Database($config['db']['connections'], 'db1');

        return $db;
    }
}
