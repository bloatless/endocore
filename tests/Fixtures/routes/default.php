<?php

return [
    'home' => [
        'method' => 'GET',
        'pattern' => '/',
        'handler' => 'Bloatless\Endocore\Tests\Fixtures\Action\HelloWorldAction',
    ],

    'invalid_action' => [
        'method' => 'GET',
        'pattern' => '/invalid-action',
        'handler' => 'Bloatless\Endocore\Tests\Fixtures\InvalidAction',
    ],
];
