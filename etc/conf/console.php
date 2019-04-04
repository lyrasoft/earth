<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

$assetBuild = 'default bootstrap';

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
            'php windwalker asset sync unidev -f',
        ],

        // Prepare assets and install dependencies
        'prepare' => [
            'php windwalker run makelink',
            'yarn install',
            'yarn prod ' . $assetBuild,
        ],

        // Prepare for development and reset migration
        'preparedev' => [
            'php windwalker run makelink',
            'yarn build ' . $assetBuild,
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
        ]
    ]
];
