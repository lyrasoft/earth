<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

return [
    'captcha' => [
        'default' => env('CAPTCHA_DEFAULT') ?? 'none',
        'recaptcha' => [
            'driver' => 'recaptcha',
            'key' => env('RECAPTCHA_KEY'),
            'secret' => env('RECAPTCHA_SECRET'),
            'type' => env('RECAPTCHA_TYPE') ?? 'checkbox'
        ],
        'image' => [
            'driver' => 'gregwar',
            'session_key' => 'captcha_content',
            'lifetime' => 300,
            'quality' => 90
        ],
        'none' => [
            'driver' => 'none'
        ]
    ],
    'image' => [
        'storage' => env('IMAGE_UPLOAD_STORAGE') ?? 'local',
        'resize' => [
            'enabled' => true,
            'width' => 1200,
            'height' => 1200,
            'crop' => false,
            'quality' => 85
        ]
    ],
    'amazon' => [
        'key' => env('AWS_ACCESS_KEY'),
        'secret' => env('AWS_SECRET'),
        'bucket' => env('AWS_S3_BUCKET'),
        'subfolder' => env('AWS_S3_SUBFOLDER'),
        'endpoint' => env('AWS_S3_ENDPOINT'),
        'region' => env('AWS_S3_REGION')
    ],
    'imgur' => [
        'key' => env('IMGUR_APP_KEY'),
        'secret' => env('IMGUR_APP_SECRET'),
        'album' => ''
    ],
    'local' => [
        'path' => env('IMAGE_UPLOAD_LOCAL_PATH') ?? 'asset/upload'
    ]
];
