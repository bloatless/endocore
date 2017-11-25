<?php

return [
    'home' => [
        'method' => 'GET',
        'pattern' => '/',
        'handler' => [
            'action' => 'Nekudo\ShinyCoreApp\Actions\HomeAction',
            'domain' => 'Nekudo\ShinyCoreApp\Domains\HomeDomain',
            'responder' => 'Nekudo\ShinyCoreApp\Responder\HomeResponder',
        ],
    ],
];
