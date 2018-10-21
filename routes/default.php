<?php

return [
    'home' => [
        'method' => 'GET',
        'pattern' => '/',
        'handler' => 'Nekudo\ShinyCoreApp\Actions\HomeAction',
    ],
    'json' => [
        'method' => 'GET',
        'pattern' => '/json',
        'handler' => 'Nekudo\ShinyCoreApp\Actions\JsonDemoAction'
    ]
];
