<?php

return [
    'test' => 1,
    'channels' => [
        'smartlog-stack' => [
            'driver' => 'stack',
            'channels' => ['single', 'smartlog'],
            'ignore_exceptions' => false
        ],

        'smartlog' => [
            'driver' => 'monolog',
            'handler' => \SmartContact\SmartLogClient\LogHandlers\SmartLogHandler::class,
            'level' => 'emergency'
        ]

        // 'smartlog' => [
        //     'driver' => 'custom',
        //     'via' => \SmartContact\SmartLogClient\SmartLogChannel::class,
        //     'level' => 'emergency'
        // ]
    ]
];
