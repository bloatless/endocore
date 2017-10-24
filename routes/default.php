<?php

return [
    'home' => [
        'method' => 'GET',
        'pattern' => '/',
        'handler' => [
            'action' => 'Nekudo\ShinyCoreApp\Actions\HomeAction',
            'domain' => '',
        ],
    ],
];
