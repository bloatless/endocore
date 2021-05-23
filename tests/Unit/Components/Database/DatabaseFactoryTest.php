<?php

namespace Bloatless\Endocore\Tests\Unit\Components\Database;

use Bloatless\Endocore\Components\Database\Database;
use Bloatless\Endocore\Components\Database\DatabaseFactory;
use Bloatless\Endocore\Components\Database\Exception\DatabaseException;

class DatabaseFactoryTest extends AbstractDatabaseTest
{

    public function testMake()
    {
        $config = include TESTS_ROOT . '/Fixtures/config/config.php';
        $factory = new DatabaseFactory($config);
        $db = $factory->make();
        $this->assertInstanceOf(Database::class, $db);
    }

    public function testInitWithoutConnections()
    {
        $config = include TESTS_ROOT . '/Fixtures/config/config.php';
        unset($config['db']);
        $factory = new DatabaseFactory($config);
        $this->expectException(DatabaseException::class);
        $factory->make();
    }
}
