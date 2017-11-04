<?php

namespace Nekudo\ShinyCore\Tests\Integration;

use Nekudo\ShinyCore\Application;
use Nekudo\ShinyCore\Request;
use Nekudo\ShinyCore\Router;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testApplicationCanBeInitiated()
    {
        $config = include __DIR__ . '/../Mocks/config.php';
        $routes = include __DIR__ . '/../Mocks/routes.php';
        $request = new Request;
        $router = new Router($routes);
        $app = new Application($config, $request, $router);
        $this->assertInstanceOf('Nekudo\ShinyCore\Application', $app);
        $this->assertInstanceOf('Nekudo\ShinyCore\Interfaces\RouterInterface', $app->router);


    }
}
