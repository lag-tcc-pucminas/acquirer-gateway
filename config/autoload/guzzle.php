<?php

declare(strict_types=1);

return [
    'acquiring-rules' => [
        'client' => [
            'base_uri' => env('ACQUIRING_RULES_URL'),
            'http_errors' => false,
            'timeout' => env('ACQUIRING_RULES_REQUEST_TIMEOUT', 5),
            'connect_timeout' => 5,
            'headers' => [
                'User-Agent' => env('APP_NAME', 'acquirer-gateway')
            ]
        ],
        'pool' => [
            'max_connections' => 10,
        ]
    ]
];