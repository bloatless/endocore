<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/../config.php';
$routes = require_once __DIR__ . '/../routes/default.php';

$router = new \Nekudo\ShinyCore\Router($routes);

$app = new \Nekudo\ShinyCore\Application($config, $router);

return $app;
