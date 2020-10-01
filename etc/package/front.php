<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

return [
    'providers' => [

    ],

    'routing' => [
        'files' => [
            'warder' => \Lyrasoft\Warder\Helper\WarderHelper::getFrontendRouting(),
            'luna' => \Lyrasoft\Luna\Helper\LunaHelper::getFrontendRouting(),
        ]
    ],

    'middlewares' => [
        \Windwalker\DI\Container::meta(
            \Windwalker\Core\Application\Middleware\OfflineWebMiddleware::class,
            [
                'options' => [
                    'enabled' => env('SYSTEM_OFFLINE'),
                    'tester' => env('SYSTEM_TESTER'),
                ]
            ]
        ),
        \Windwalker\Core\Application\Middleware\SefWebMiddleware::class
    ],

    'configs' => [

    ],

    'listeners' => [
        'orphans' => \Phoenix\Listener\DumpOrphansListener::class,
        'view' => \Front\Listener\ViewListener::class
    ],

    'console' => [
        'commands' => [

        ]
    ],
];
