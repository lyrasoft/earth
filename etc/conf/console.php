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
        'makelink' => [
            'php windwalker asset sync phoenix -f',
            'php windwalker asset sync luna -f',
            'php windwalker asset sync warder -f',
            'php windwalker asset sync unidev -f',
        ],

        // Prepare assets and install dependencies
        'prepare' => [
            'php windwalker run makelink',
            'yarn install',
            'yarn build default bootstrap',
        ],

        // Prepare for development and reset migration
        'preparedev' => [
            'cross-env NODE_ENV=development php windwalker run prepare',
            'php windwalker unidev bladeopt',
            'php windwalker migration reset --seed',
            [
                'cmd' => 'lyra pstorm sniffer -p',
                'ignore_error' => true
            ],
        ],

        // Deploy new version
        'deploy' => [
            'git pull',
            'composer install --no-dev',
            'cross-env APP_ENV=dev php windwalker migration migrate',
            'cross-env NODE_ENV=production php windwalker run prepare',
            'php windwalker asset makesum',
        ],
    ]
];
