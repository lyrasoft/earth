<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

use Windwalker\Core\Router\RouteCreator;

/** @var RouteCreator $router */
$router->any('home', '/')
    ->controller('Category')
    ->requirement('page', '\d+')
    ->extra('layout', 'category')
    ->extra('menu', 'mainmenu')
    ->extra('category', [
        'type' => 'article',
        'model' => 'Articles',
        'view' => 'Category',
        'ordering' => 'article.created',
        'direction' => 'DESC',
        'deep' => true
    ]);
