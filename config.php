<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2019 __ORGANIZATION__.
 * @license    __LICENSE__
 */

return [
    'console' => [
        'scripts' => [
            'prepare' => [
                'php windwalker asset sync phoenix -f',
                'php windwalker asset sync luna -f',
                'php windwalker asset sync unidev -f',
                'yarn install',
                'bower install',
                'yarn prod default bootstrap'
            ],
            'preparedev' => [
                'echo dev > .mode',
                'php windwalker run prepare',
                'php windwalker unidev bladeopt',
                'php windwalker migration reset --seed'
            ],
            'deploy' => [
                'git pull',
                'composer install',
                'php windwalker run prepare',
                'php windwalker migration migrate',
                'php windwalker asset makesum',
                'echo prod > .mode'
            ]
        ]
    ],
];
