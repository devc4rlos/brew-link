<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AWS SDK Configuration
    |--------------------------------------------------------------------------
    |
    | Here we define the base configuration for the AWS SDK. The 'endpoint'
    | field is crucial: if it is set (LocalStack), the SDK ignores the real
    | cloud and points to your local container instead.
    |
    */
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    'version' => 'latest',

    'endpoint' => env('AWS_ENDPOINT'),

    'credentials' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
    ],

    'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', true),
];
