<?php

return [
    'home' => [
        'method' => 'GET',
        'pattern' => '/',
        'handler' => 'Bloatless\Endocore\Tests\Fixtures\HelloWorldHtmlAction',
    ],

    'invalid_action' => [
        'method' => 'GET',
        'pattern' => '/invalid-action',
        'handler' => 'Bloatless\Endocore\Tests\Fixtures\InvalidAction',
    ],
];
