<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2020 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

return [
    'debug' => (bool) (env('APP_DEBUG') ?? false),

    'mode' => env('APP_ENV') ?? 'prod',

    'dump_server' => [
        'host' => env('DUMP_SERVER_HOST') ?: 'tcp://127.0.0.1:9912'
    ]
];