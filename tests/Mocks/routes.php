<?php

return [
    'home' => [
        'method' => 'GET',
        'pattern' => '/',
        'handler' => [
            'action' => 'Nekudo\ShinyCore\Tests\Mocks\HelloWorldAction',
        ],
    ],
];
