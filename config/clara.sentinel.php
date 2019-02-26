<?php

/**
 * Default config values
 */
return [
    
    'route' => [
        'web' => [
            'middleware' => ['web', \CeddyG\Clara\Http\Middleware\SentinelAccessMiddleware::class]
        ],
        'api' => [
            'prefix'    => 'api/admin',
            'middleware' => ['api', \CeddyG\Clara\Http\Middleware\SentinelAccessMiddleware::class.':api']
        ]
    ]
    
];
