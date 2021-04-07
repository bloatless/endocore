<?php

namespace Bloatless\Endocore\Components\BasicAuth\Tests\Unit\AuthBackend;

use Bloatless\Endocore\Components\BasicAuth\AuthBackend\MysqlAuthBackend;
use Bloatless\Endocore\Components\BasicAuth\Tests\Unit\DatabaseTest;
use Bloatless\Endocore\Components\QueryBuilder\Factory as QueryBuilderFactory;

class MysqlAuthBackendTest extends DatabaseTest
{
    /**
     * @var array $config
     */
    public $config;

    public $queryBuilderFactory;

    public function setUp(): void
    {
        parent::setUp();
        $this->config = include TESTS_ROOT . '/Fixtures/config.php';
        $this->queryBuilderFactory = new QueryBuilderFactory($this->config['db']);
    }

    public function testCanBeInitialized()
    {
        $authBackend = new MysqlAuthBackend($this->queryBuilderFactory);
        $this->assertInstanceOf(MysqlAuthBackend::class, $authBackend);
    }

    public function testValidateCredentials()
    {
        $authBackend = new MysqlAuthBackend($this->queryBuilderFactory);

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
