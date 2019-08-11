<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

return [
    // Session handler, supports `native`, `database`, `apc`, `memcached`
    'handler' => 'database',

    // By minutes
    'expire_time' => 150,

    'cookie_path' => null,

    // Session database options
    'database' => [
        'table' => 'sessions'
    ]
];
