<?php

namespace Nekudo\ShinyCore\Tests\Unit\Router;

use Nekudo\ShinyCore\Router\Router;
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
        $this->assertInternalType('array', $routeInfo);
        $this->assertEquals($routeInfo[0], Router::FOUND);
        $this->assertEquals($routeInfo[1], 'home');
    }
}
