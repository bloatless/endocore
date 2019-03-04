<?php

namespace Bloatless\Endocore\Tests\Unit\Router;

use Bloatless\Endocore\Components\Router\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function testDispatch()
    {
        $routes = [
            'home' => [
                'method' => 'GET',
                'pattern' => '/',
                'handler' => 'home',
            ]
        ];

        $router = new Router($routes);
        $routeInfo = $router->dispatch('GET', '/');
        $this->assertIsArray($routeInfo);
        $this->assertEquals($routeInfo[0], Router::FOUND);
        $this->assertEquals($routeInfo[1], 'home');
    }
}
