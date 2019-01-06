<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2019 __ORGANIZATION__.
 * @license    __LICENSE__
 */

use Windwalker\Core\Router\RouteCreator;

/** @var $router RouteCreator */

$router->any('home', '/')
    ->controller('Dashboard')
    ->extraValues([
        'layout' => 'dashboard',
        'active' => [
            'manimenu' => 'dashboards'
        ],
    ]);
