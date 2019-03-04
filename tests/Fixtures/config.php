<?php

return [
    'logger' => [
        'path_logs' => __DIR__ . '/logs',
        'min_level' => 'debug',
    ],

    'templating' => [
        'path_views' => __DIR__ . '/resources/views',
        'path_layouts' => __DIR__ . '/resources/layouts',
    ],

    'db' => [
        'connections' => [
            'db1' => [
                'driver' => 'mysql',
                'host' => 'localhost',
                'database' => 'endocore_test',
                'username' => 'endocore',
                'password' => '',
                'charset' => 'utf8', // Optional
                'timezone' => 'Europe/Berlin', // Optional
            ]
        ],

        'default_connection' => 'db1',
    ],
];
