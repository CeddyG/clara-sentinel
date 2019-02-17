<?php

/**
 * Default config values
 */
return [
    
    'route' => [
        'web' => [
            'middleware' => ['web']
        ],
        'web-admin' => [
            'prefix'    => 'admin',
            'middleware' => ['web', 'access']
        ],
        'api' => [
            'prefix'    => 'api/admin',
            'middleware' => ['api', 'access']
        ]
    ]
    
];
