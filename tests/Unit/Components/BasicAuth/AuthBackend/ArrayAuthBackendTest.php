<?php

namespace Bloatless\Endocore\Tests\Unit\Components\BasicAuth\AuthBackend;

use Bloatless\Endocore\Components\BasicAuth\AuthBackend\ArrayAuthBackend;
use PHPUnit\Framework\TestCase;

class ArrayAuthBackendTest extends TestCase
{
    /**
     * @var array $config
     */
    public $config;

    public $users;

    public function setUp(): void
    {
        parent::setUp();
        $this->config = include TESTS_ROOT . '/Fixtures/config.php';
        $this->users = $this->config['auth']['backends']['array']['users'];
    }

    public function testGetSetUsers()
    {
        // set via config
        $auth = new ArrayAuthBackend($this->users);
        $users = $auth->getUsers();
        $this->assertIsArray($users);
        $this->assertArrayHasKey('foo', $users);
        $this->assertEquals($this->users['foo'], $users['foo']);

        // set via setter
        $auth = new ArrayAuthBackend([]);
        $this->assertEquals([], $auth->getUsers());
        $auth->setUsers($this->users);
        $users = $auth->getUsers();
        $this->assertArrayHasKey('foo', $users);
        $this->assertEquals($this->users['foo'], $users['foo']);
    }

    public function testValidateCredentials()
    {
        // test with empty users array
        $authBackend = new ArrayAuthBackend([]);
        $res = $authBackend->validateCredentials('foo', 'bar');
        $this->assertFalse($res);

        // test with unknown user
        $authBackend = new ArrayAuthBackend($this->users);
        $res = $authBackend->validateCredentials('unknown', 'bar');
        $this->assertFalse($res);

        // test with valid password
        $authBackend = new ArrayAuthBackend($this->users);
        $res = $authBackend->validateCredentials('foo', 'bar');
        $this->assertTrue($res);

        // test with invalid password
        $res = $authBackend->validateCredentials('foo', 'baz');
        $this->assertFalse($res);
    }
}
