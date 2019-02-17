<?php

/**
 * Default config values
 */
return [
    
    'route' => [
        'web' => [
            'prefix'    => 'admin',
            'middleware' => ['web', 'access']
        ],
        'api' => [
            'prefix'    => 'api/admin',
            'middleware' => ['api', 'access']
        ]
    ],
    
];
