<?php

namespace Nekudo\ShinyCore\Tests\Unit\Database\ConnectionAdapter;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Database\ConnectionAdapter\PdoMysql;
use Nekudo\ShinyCore\Exception\Application\DatabaseException;
use Nekudo\ShinyCore\Tests\Unit\Database\DatabaseTest;

class PdoMysqlTest extends DatabaseTest
{
    public function testConnect()
    {
        $configData = include SC_TESTS . '/Mocks/config.php';
        $config = (new Config)->fromArray($configData);
        $credentials = $config->getDefaultDbConfig();
        $credentials['port'] = 3306;
        $adapter = new PdoMysql;

        // test valid credentials:
        $connection = $adapter->connect($credentials);
        $this->assertInstanceOf(\PDO::class, $connection);

        // test invalid credentials:
        $this->expectException(DatabaseException::class);
        $adapter->connect([]);

        // test with invalid timezone:
        $credentials['timezone'] = 'Springfield';
        $connection = $adapter->connect($credentials);
        $this->assertInstanceOf(\PDO::class, $connection);
    }
}
