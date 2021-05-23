<?php

namespace Bloatless\Endocore\Tests\Integration;

use Bloatless\Endocore\Application;
use Bloatless\Endocore\Contracts\ErrorHandler\ErrorHandlerContract;
use Bloatless\Endocore\Contracts\Router\RouterContract;
use Bloatless\Endocore\Core\Http\Exception\MethodNotAllowedException;
use Bloatless\Endocore\Core\Http\Exception\NotFoundException;
use Bloatless\Endocore\Core\Http\Request;
use Bloatless\Endocore\Exception\Application\EndocoreException;
use League\Container\Container;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public $config;

    public function setUp(): void
    {
        $this->config = include TESTS_ROOT . '/Fixtures/config/config.php';
    }

    public function testApplicationCanBeInitiated()
    {

        $app = new Application(TESTS_ROOT . '/Fixtures/');
        $this->assertInstanceOf('Bloatless\Endocore\Application', $app);
        $this->assertInstanceOf(RouterContract::class, $app->router);
        $this->assertInstanceOf(ErrorHandlerContract::class, $app->errorHandler);
        $this->assertInstanceOf(Container::class, $app->container);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testValidRoute()
    {
        $request = new Request([], [], [
           'REQUEST_METHOD' => 'GET',
           'REQUEST_URI' => '/'
        ]);

        $app = new Application(TESTS_ROOT . '/Fixtures/');
        $this->expectOutputString('Hello World!');
        $response = $app->handle($request);
        $this->assertEquals('Hello World!', $response->getBody());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testNotFoundRoute()
    {
        $request = new Request([], [], [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/qwertz'
        ]);
        $app = new Application(TESTS_ROOT . '/Fixtures/');
        $this->expectException(NotFoundException::class);
        $response = $app->handle($request);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testMethodNotAllowedRequest()
    {
        $request = new Request([], [], [
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => '/'
        ]);
        $app = new Application(TESTS_ROOT . '/Fixtures/');
        $this->expectException(MethodNotAllowedException::class);
        $response = $app->handle($request);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testInvalidActionRequest()
    {
        $request = new Request([], [], [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/invalid-action'
        ]);
        $app = new Application(TESTS_ROOT . '/Fixtures/');
        $this->expectException(EndocoreException::class);
        $response = $app->handle($request);
    }
}
