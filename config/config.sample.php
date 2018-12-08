<?php

return [
    'paths' => [
        'views' => __DIR__ . '/../resources/views',
        'layouts' => __DIR__ . '/../resources/views/layouts',
        'logs' => __DIR__ . '/../logs',
    ],

    'classes' => [
        'html_renderer' => '\Nekudo\ShinyCore\Responder\PhtmlRenderer',
    ],

    'db' => [
        'connections' => [
            'db1' => [
                'driver' => 'mysql',
                'host' => 'localhost',
                'database' => 'db1',
                'username' => 'root',
                'password' => 'your-password',
                'charset' => 'utf8', // Optional
                'timezone' => 'Europe/Berlin', // Optional
            ]
        ],

        'default_connection' => 'db1',
    ],

    'logger' => [
        'min_level' => 'warning',
    ],
];
