<?php

namespace Bloatless\Endocore\Tests\Unit\Components\BasicAuth;

use Bloatless\Endocore\Components\BasicAuth\AuthBackend\ArrayAuthBackend;
use Bloatless\Endocore\Components\BasicAuth\BasicAuth;
use Bloatless\Endocore\Components\Core\Http\Request;
use Bloatless\Endocore\Components\Core\Http\Response;

class BasicAuthTest extends DatabaseTest
{
    protected $config;

    protected $arrayBackend;

    public function setUp(): void
    {
        parent::setUp();
        $this->config = include TESTS_ROOT . '/Fixtures/config.php';
        $this->arrayBackend = new ArrayAuthBackend($this->config['auth']['backends']['array']['users']);
    }

    public function testIsAuthenticatedWithoutHTTPHeader()
    {
        // test without auth header
        $auth = new BasicAuth($this->arrayBackend);
        $request = new Request;
        $result = $auth->isAuthenticated($request);
        $this->assertFalse($result);
    }

    public function testIsAuthenticatedWithValidHttpHeader()
    {
        $request = new Request([], [], [
            'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode('foo:bar'),
        ]);
        $auth = new BasicAuth($this->arrayBackend);
        $result = $auth->isAuthenticated($request);
        $this->assertTrue($result);
    }

    public function testIsAuthenticatedWithInvalidHttpHeader()
    {
        $auth = new BasicAuth($this->arrayBackend);

        // Header is missing "Basic" keyword
        $request = new Request([], [], [
            'HTTP_AUTHORIZATION' => 'cisaB ' . base64_encode('foo:bar'),
        ]);
        $result = $auth->isAuthenticated($request);
        $this->assertFalse($result);

        // Header with invalid base64 encoding
        $request = new Request([], [], [
            'HTTP_AUTHORIZATION' => 'Basic FooBarBz',
        ]);
        $result = $auth->isAuthenticated($request);
        $this->assertFalse($result);
    }

    public function testIsAuthenticatedWithInvalidUsername()
    {
        $request = new Request([], [], [
            'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode('invalid:bar'),
        ]);
        $auth = new BasicAuth($this->arrayBackend);
        $result = $auth->isAuthenticated($request);
        $this->assertFalse($result);
    }

    public function testIsAuthenticatedWithInvalidPassword()
    {
        $request = new Request([], [], [
            'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode('foo:naa'),
        ]);
        $auth = new BasicAuth($this->arrayBackend);
        $result = $auth->isAuthenticated($request);
        $this->assertFalse($result);
    }

    public function testGetUsernameFromRequestWithValidRequest()
    {
        $request = new Request([], [], [
            'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode('foo:bar'),
        ]);
        $auth = new BasicAuth($this->arrayBackend);
        $this->assertEquals('foo', $auth->getUsernameFromRequest($request));
    }

    public function testGetUsernameFromRequestWithInvalidRequest()
    {
        $request = new Request([], [], []);
        $auth = new BasicAuth($this->arrayBackend);
        $this->assertEquals('', $auth->getUsernameFromRequest($request));
    }

    public function testRequestAuthorization()
    {
        $auth = new BasicAuth($this->arrayBackend);
        $response = $auth->requestAuthorization();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(401, $response->getStatus());

        $headers = $response->getHeaders();
        $this->assertIsArray($headers);
        $this->assertEquals([
            'WWW-Authenticate' => 'Basic realm="Restricted access"',
        ], $headers);
    }
}
