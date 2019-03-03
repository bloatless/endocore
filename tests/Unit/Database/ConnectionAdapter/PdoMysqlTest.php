<?php

namespace Bloatless\Endocore\Tests\Unit\Database\ConnectionAdapter;

use Bloatless\Endocore\Config;
use Bloatless\Endocore\Database\ConnectionAdapter\PdoMysql;
use Bloatless\Endocore\Exception\Application\DatabaseException;
use PHPUnit\Framework\TestCase;

class PdoMysqlTest extends TestCase
{
    public $config;

    public function setUp(): void
    {
        $configData = include SC_TESTS . '/Fixtures/config.php';
        $this->config = (new Config)->fromArray($configData);
    }

    public function testConnectWithValidCredentails()
    {
        $credentials = $this->config->getDefaultDbConfig();
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
        $credentials = $this->config->getDefaultDbConfig();
        $credentials['timezone'] = 'Springfield';
        $this->expectException(\Exception::class);
        $connection = $adapter->connect($credentials);

    }
}
