<?php

return [
    'channels' => [
        'smartlog-stack' => [
            'driver' => 'stack',
            'channels' => [
                'single',
                'smartlog',
                config('smartlog.notificationChannel') || null
            ],
            'ignore_exceptions' => false
        ],

        'smartlog' => [
            'driver' => 'monolog',
            'handler' => \SmartContact\SmartLogClient\LogHandlers\SmartLogHandler::class,
            'level' => config('smartlog.logLevel')
        ]
    ]
];
