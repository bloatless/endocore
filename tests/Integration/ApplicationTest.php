<?php

namespace Nekudo\ShinyCore\Tests\Integration;

use Nekudo\ShinyCore\Application;
use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Exception\ExceptionHandler;
use Nekudo\ShinyCore\Logger\NullLogger;
use Nekudo\ShinyCore\Http\Request;
use Nekudo\ShinyCore\Router\Router;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    /**
     * @var Config $config
     */
    public $config;

    public $routes;

    public $logger;

    public $exceptionHandler;

    public function setUp()
    {
        $config = include __DIR__ . '/../Mocks/config.php';
        $this->config = (new Config)->fromArray($config);
        $this->routes = include __DIR__ . '/../Mocks/routes.php';
        $this->logger = new NullLogger;
        $request = new Request;
        $this->exceptionHandler = new ExceptionHandler($this->config, $this->logger, $request);
    }

    public function testApplicationCanBeInitiated()
    {
        $request = new Request;
        $router = new Router($this->routes);
        $app = new Application(
            $this->config,
            $request,
            $router,
            $this->logger,
            $this->exceptionHandler
        );
        $this->assertInstanceOf('Nekudo\ShinyCore\Application', $app);
        $this->assertInstanceOf('Nekudo\ShinyCore\Http\Request', $app->request);
        $this->assertInstanceOf('Nekudo\ShinyCore\Router\RouterInterface', $app->router);
        $this->assertInstanceOf('Nekudo\ShinyCore\Logger\LoggerInterface', $app->logger);
        $this->assertInstanceOf('Nekudo\ShinyCore\Exception\ExceptionHandler', $app->exceptionHandler);
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
        $router = new Router($this->routes);
        $app = new Application($this->config, $request, $router, $this->logger, $this->exceptionHandler);
        $this->expectOutputString('Hello World!');
        $response = $app->run();
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
        $router = new Router($this->routes);
        $app = new Application($this->config, $request, $router, $this->logger, $this->exceptionHandler);
        $this->expectOutputRegex('/not found/i');
        $response = $app->run();
        $this->assertEquals(404, $response->getStatus());
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
        $router = new Router($this->routes);
        $app = new Application($this->config, $request, $router, $this->logger, $this->exceptionHandler);
        $this->expectOutputRegex('/method not allowed/i');
        $response = $app->run();
        $this->assertEquals(405, $response->getStatus());
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
        $router = new Router($this->routes);
        $app = new Application($this->config, $request, $router, $this->logger, $this->exceptionHandler);
        $this->expectOutputRegex('/Action class not found/i');
        $response = $app->run();
        $this->assertEquals(500, $response->getStatus());
    }
}
