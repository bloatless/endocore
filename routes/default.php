<?php

return [
    'home' => [
        'method' => 'GET',
        'pattern' => '/',
        'handler' => 'Nekudo\ShinyCoreApp\Actions\HomeAction',
    ],
    'about' => [
        'method' => 'GET',
        'pattern' => '/about',
        'handler' => 'Nekudo\ShinyCoreApp\Actions\AboutAction',
    ],
    'json' => [
        'method' => 'GET',
        'pattern' => '/json',
        'handler' => 'Nekudo\ShinyCoreApp\Actions\JsonDemoAction'
    ]
];
