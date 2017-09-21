<?php
namespace Nekudo\ShinyCore;

class Application
{
    /**
     * @var array $config
     */
    public $config;

    /**
     * @var array $routes
     */
    public $routes;


    public function __construct(array $config, array $routes)
    {
        $this->config = $config;
        $this->routes = $routes;
    }
}
