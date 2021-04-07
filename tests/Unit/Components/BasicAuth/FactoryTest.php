<?php

namespace Bloatless\Endocore\Components\BasicAuth\Tests\Unit;

use Bloatless\Endocore\Components\BasicAuth\BasicAuth;
use Bloatless\Endocore\Components\BasicAuth\BasicAuthException;
use Bloatless\Endocore\Components\BasicAuth\Factory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var array $config
     */
    public $config;

    public function setUp(): void
    {
        parent::setUp();
        $this->config = include TESTS_ROOT . '/Fixtures/config.php';
    }

    public function testMakeAuthWithMissingConfig()
    {
        // test with missing config
        $this->expectException(BasicAuthException::class);
        $factory = new Factory([]);
        $basicAuth = $factory->makeAuth();
    }

    public function testMakeAuthWithArrayBackendConfig()
    {

        // test with array-backend
        $config = $this->config;
        $config['auth']['backend'] = 'array';
        $factory = new Factory($config);
        $basicAuth = $factory->makeAuth();
        $this->assertInstanceOf(BasicAuth::class, $basicAuth);
    }

    public function testMakeAuthWithMysqlBackendConfig()
    {

        // test with mysql backend
        $config = $this->config;
        $factory = new Factory($config);
        $basicAuth = $factory->makeAuth();
        $this->assertInstanceOf(BasicAuth::class, $basicAuth);

        // test with missing db config
        $config = $this->config;
        unset($config['db']);
        $factory = new Factory($config);
        $this->expectException(BasicAuthException::class);
        $factory->makeAuth();
    }

    public function testMakeAuthWithInvalidBackendConfig()
    {
        // test with invalid backend
        $config = $this->config;
        $config['auth']['backend'] = 'foo';
        $factory = new Factory($config);
        $this->expectException(BasicAuthException::class);
        $basicAuth = $factory->makeAuth();
    }
}
