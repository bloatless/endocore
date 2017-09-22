<?php
namespace Nekudo\ShinyCore;

use Nekudo\ShinyCore\Interfaces\RouterInterface;

class Application
{
    /**
     * @var array $config
     */
    public $config;

    /**
     * @var RouterInterface $router
     */
    public $router;

    public function __construct(array $config, RouterInterface $router)
    {
        $this->config = $config;
        $this->router = $router;
    }

    public function run()
    {
        $foo = $this->router->dispatch();
        var_dump($foo);
    }
}
