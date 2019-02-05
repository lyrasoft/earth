<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

return [
    // Custom scripts, add some commands here to batch execute. Example:
    // 'scripts' => [
    //     'foo' => [
    //         'git pull'
    //         'composer install'
    //         'php windwalker migration migrate'
    //
    // Then just run `$ php windwalker run foo`
    'scripts' => [
        // Prepare assets and install dependencies
        'prepare' => [
            'php windwalker asset sync phoenix -f',
            'php windwalker asset sync luna -f',
            'php windwalker asset sync unidev -f',
            'yarn install',
            'yarn prod default bootstrap'
        ],

        // Prepare for development and reset migration
        'preparedev' => [
            'echo dev > .mode',
            'php windwalker run prepare',
            'php windwalker unidev bladeopt',
            'php windwalker migration reset --seed',
            'lyra pstorm sniffer',
        ],

        // Deploy new version
        'deploy' => [
            'git pull',
            'composer install --no-dev',
            'php windwalker migration migrate',
            'php windwalker run prepare',
            'php windwalker asset makesum',
            'echo prod > .mode'
        ]
    ]
];
