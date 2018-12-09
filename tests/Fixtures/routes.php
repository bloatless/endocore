<?php

return [
    'home' => [
        'method' => 'GET',
        'pattern' => '/',
        'handler' => 'Nekudo\ShinyCore\Tests\Fixtures\HelloWorldHtmlAction',
    ],

    'invalid_action' => [
        'method' => 'GET',
        'pattern' => '/invalid-action',
        'handler' => 'Nekudo\ShinyCore\Tests\Fixtures\InvalidAction',
    ],
];
