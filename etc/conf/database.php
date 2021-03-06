<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2020 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

use Windwalker\Core\Provider\DatabaseProvider;
use Windwalker\Database\DatabaseAdapter;

use Windwalker\Database\DatabaseFactory;

use function Windwalker\DI\create;
use function Windwalker\ref;

return [
    'default' => 'local',

    'connections' => [
        'local' => ref('factories.instances.local'),
    ],

    'providers' => [
        DatabaseProvider::class,
    ],

    'bindings' => [
        //
    ],

    'factories' => [
        'instances' => [
            'local' => fn (DatabaseFactory $factory) => $factory->create(
                env('DATABASE_DRIVER'),
                [
                    'host' => env('DATABASE_HOST') ?: 'localhost',
                    'database' => env('DATABASE_NAME'),
                    'username' => env('DATABASE_USER'),
                    'password' => env('DATABASE_PASSWORD'),
                    'port' => env('DATABASE_PORT'),
                    'prefix' => env('DATABASE_TABLE_PREFIX'),
                ]
            ),
        ],
    ],
];
