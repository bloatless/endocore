<?php

namespace Bloatless\Endocore\Tests\Unit\Components\BasicAuth\AuthBackend;

use Bloatless\Endocore\Components\BasicAuth\AuthBackend\MysqlAuthBackend;
use Bloatless\Endocore\Components\Database\Database;
use Bloatless\Endocore\Components\Database\DatabaseFactory;
use Bloatless\Endocore\Tests\Unit\Components\BasicAuth\DatabaseTest;

class MysqlAuthBackendTest extends DatabaseTest
{
    private Database $db;

    public function setUp(): void
    {
        parent::setUp();
        $config = include TESTS_ROOT . '/Fixtures/config.php';
        $dbFactory = new DatabaseFactory($config);
        $this->db = $dbFactory->make();
    }

    public function testCanBeInitialized()
    {
        $authBackend = new MysqlAuthBackend($this->db);
        $this->assertInstanceOf(MysqlAuthBackend::class, $authBackend);
    }

    public function testValidateCredentials()
    {
        $authBackend = new MysqlAuthBackend($this->db);

        // test with unknown user
        $res = $authBackend->validateCredentials('unknown', 'bar');
        $this->assertFalse($res);

        // test with valid credentials
        $res = $authBackend->validateCredentials('foo', 'bar');
        $this->assertTrue($res);

        // test with invalid credentials
        $res = $authBackend->validateCredentials('foo', 'baz');
        $this->assertFalse($res);
    }
}
