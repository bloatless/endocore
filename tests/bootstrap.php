<?php

/** @var \Composer\Autoload\ClassLoader $autoloader */
$autoloader = require __DIR__ . '/../vendor/autoload.php';

// Register test classes
$autoloader->addPsr4('Nekudo\ShinyCore\Tests\\', __DIR__);