<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/../config/config.php';
$routes = require_once __DIR__ . '/../routes/default.php';

$app = new \Nekudo\ShinyCore\Application(
    $config,
    (new \Nekudo\ShinyCore\Request($_GET, $_POST, $_SERVER)),
    (new \Nekudo\ShinyCore\Router($routes))
);

return $app;
