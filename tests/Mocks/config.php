<?php

return [
    'paths' => [
        'views' => __DIR__ . '/resources/views',
        'layouts' => __DIR__ . '/resources/layouts',
        'logs' => __DIR__ . '/logs',
    ],

    'classes' => [
        'html_renderer' => '\Nekudo\ShinyCore\Responder\PhtmlRenderer',
    ],

    'db' => []
];
