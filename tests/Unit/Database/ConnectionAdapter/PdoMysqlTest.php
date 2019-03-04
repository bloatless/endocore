<?php

namespace Bloatless\Endocore\Tests\Unit\Database\ConnectionAdapter;

use Bloatless\Endocore\Components\Database\ConnectionAdapter\PdoMysql;
use Bloatless\Endocore\Components\Database\Exception\DatabaseException;
use PHPUnit\Framework\TestCase;

class PdoMysqlTest extends TestCase
{
    public $config;

    public $defaultCredentials;

    public function setUp(): void
    {
        $configData = include SC_TESTS . '/Fixtures/config.php';
        $this->config = $configData['db'];
        $defaultConnection = $this->config['default_connection'];
        $this->defaultCredentials = $this->config['connections'][$defaultConnection];
    }

    public function testConnectWithValidCredentails()
    {
        $credentials = $this->defaultCredentials;
        $credentials['port'] = 3306;
        $adapter = new PdoMysql;
        $connection = $adapter->connect($credentials);
        $this->assertInstanceOf(\PDO::class, $connection);
    }

    public function testConnectWithInvalidCredentails()
    {
        $this->expectException(DatabaseException::class);
        $adapter = new PdoMysql;
        $adapter->connect([]);
    }

    public function testConnectWithInvalidTimezone()
    {
        $adapter = new PdoMysql;
        $credentials = $this->defaultCredentials;
        $credentials['timezone'] = 'Springfield';
        $this->expectException(\Exception::class);
        $connection = $adapter->connect($credentials);
    }
}
