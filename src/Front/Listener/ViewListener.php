<?php
/**
 * Part of flower project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Front\Listener;

use Lyrasoft\Luna\Helper\LunaHelper;
use Lyrasoft\Luna\Repository\CategoriesRepository;
use Windwalker\Event\Event;

/**
 * The ViewListener class.
 *
 * @since  {DEPLOY_VERSION}
 */
class ViewListener
{
    /**
     * onViewBeforeRender
     *
     * @param Event $event
     *
     * @return  void
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \ReflectionException
     * @throws \Windwalker\DI\Exception\DependencyResolutionException
     */
    public function onViewBeforeRender(Event $event)
    {
        if (LunaHelper::isAdmin()) {
            return;
        }

        $view = $event['view'];

        $view['categories'] = CategoriesRepository::getInstance()
            ->type('article')
            ->hasRoot(false)
            ->onlyAvailable()
            ->getItems();
    }
}
