<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

return [
    'facebook' => [
        'enabled' => false,
        'id' => env('FACEBOOK_SOCIAL_ID'),
        'secret' => env('FACEBOOK_SOCIAL_SECRET'),
        'scope' => 'email'
    ],
    'twitter' => [
        'enabled' => false,
        'key' => env('TWITTER_SOCIAL_KEY'),
        'secret' => env('TWITTER_SOCIAL_SECRET'),
        'scope' => ''
    ],
    'google' => [
        'enabled' => false,
        'id' => env('GOOGLE_SOCIAL_ID'),
        'secret' => env('GOOGLE_SOCIAL_SECRET'),
        'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email'
    ],
    'yahoo' => [
        'enabled' => false,
        'key' => env('YAHOO_SOCIAL_KEY'),
        'secret' => env('YAHOO_SOCIAL_SECRET'),
        'scope' => ''
    ],
    'github' => [
        'enabled' => false,
        'id' => env('GITHUB_SOCIAL_ID'),
        'secret' => env('GITHUB_SOCIAL_SECRET'),
        'scope' => ''
    ]
];
