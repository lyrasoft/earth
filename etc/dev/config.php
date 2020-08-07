<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

use Windwalker\Utilities\Arr;

return Arr::mergeRecursive(
    [
        'system' => [
            'debug' => true,
            'error_reporting' => -1,
            'offline' => false,
        ],

        'cache' => [
            'enabled' => false,
        ],

        'language' => [
            'debug' => true,
        ],

        'whoops' => [
            'editor' => env('WHOOPS_EDITOR') ?? 'phpstorm',
            'blacklist' => [
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
                    ],
                    array_keys($_ENV ?? [])
                )
            ]
        ]
    ]
);
