<?php

return [
    'defaults' => [
        'guard' => 'api',
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
        'openapi' => [
            'driver' => 'jwt',
            'provider' => 'openapiapps',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'cache',
            'model' => App\Models\User::class,
        ],
        'openapiapps' => [
            'driver' => 'cache',
            'model' => App\Models\OpenapiApp::class,
        ],
    ],
];
