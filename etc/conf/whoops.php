<?php

declare(strict_types=1);

use Whoops\Run as Whoops;
use Windwalker\Core\Provider\WhoopsProvider;

return [
    'editor' => env('WHOOPS_EDITOR') ?: 'phpstorm',
    'hidden_list' => [
        '_ENV' => array_keys($_ENV ?? []),
        '_SERVER' => array_merge(
            [
                'PATH',
                'SERVER_SOFTWARE',
                'DOCUMENT_ROOT',
                'CONTEXT_DOCUMENT_ROOT',
                'SERVER_ADMIN',
                'SCRIPT_FILENAME',
                'REMOTE_PORT',
                'DATABASE_DSN',
            ],
            array_keys($_ENV ?? [])
        )
    ],
    'bindings' => [
        Whoops::class
    ],
    'providers' => [
        WhoopsProvider::class,
    ],
    'factories' => [
        'handlers' => [
            'default' => WhoopsProvider::prettyPageHandler(),
            // 'cli_server' => WhoopsProvider::cliServerHandler()
        ]
    ],
];
