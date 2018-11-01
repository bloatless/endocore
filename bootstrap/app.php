<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    // include config files:
    $configuration = require_once __DIR__ . '/../config/config.php';
    $routes = require_once __DIR__ . '/../routes/default.php';

    // init dependencies:
    $config = (new \Nekudo\ShinyCore\Config)->fromArray($configuration);
    $request = new \Nekudo\ShinyCore\Request($_GET, $_POST, $_SERVER);
    $router = new \Nekudo\ShinyCore\Router\Router($routes);
    $logger = new \Nekudo\ShinyCore\Logger\FileLogger($config);
    $exceptionHandler = new \Nekudo\ShinyCore\Exceptions\ExceptionHandler;

    // create application:
    $app = new \Nekudo\ShinyCore\Application(
        $config,
        $request,
        $router,
        $logger,
        $exceptionHandler
    );

    return $app;
} catch (\Nekudo\ShinyCore\Exceptions\Application\ShinyCoreException $e) {
    exit('Error: ' . $e->getMessage());
}
