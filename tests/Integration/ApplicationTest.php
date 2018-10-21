<?php

namespace Nekudo\ShinyCore\Tests\Integration;

use Nekudo\ShinyCore\Application;
use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Request;
use Nekudo\ShinyCore\Router\Router;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    /**
     * @var Config $config
     */
    public $config;

    public $routes;

    public function setUp()
    {
        $config = include __DIR__ . '/../Mocks/config.php';
        $this->config = (new Config)->fromArray($config);
        $this->routes = include __DIR__ . '/../Mocks/routes.php';
    }

    public function testApplicationCanBeInitiated()
    {
        $request = new Request;
        $router = new Router($this->routes);
        $app = new Application($this->config, $request, $router);
        $this->assertInstanceOf('Nekudo\ShinyCore\Application', $app);
        $this->assertInstanceOf('Nekudo\ShinyCore\Router\RouterInterface', $app->router);
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
        $app = new Application($this->config, $request, $router);

        $this->expectOutputString('Hello World!');
        $app->run();
    }
}
