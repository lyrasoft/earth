<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Front\Helper;

use Windwalker\Core\View\Helper\AbstractHelper;
use Windwalker\Ioc;
use Windwalker\Utilities\ArrayHelper;

/**
 * The MenuHelper class.
 *
 * @since  1.0
 */
class MenuHelper extends AbstractHelper
{
    const PLURAL = 'plural';
    const SINGULAR = 'singular';

    /**
     * active
     *
     * @param string $path
     * @param array  $query
     * @param string $menu
     *
     * @return string
     */
    public function active($path, $query = [], $menu = 'mainmenu')
    {
        $view = $this->getParent()->getView();

        // Match route
        $route = $path;

        if (strpos($route, '@') === false) {
            $route = $view->getPackage()->getName() . '@' . $route;
        }

        if ($view['app']->get('route.matched') == $route && $this->matchRequest($query)) {
            return 'active';
        }

        // If route not matched, we match extra values from routing.
        $routePath = $view['app']->get('route.extra.menu.' . $menu);

        $path      = array_filter(explode('/', trim($path, '/')), 'strlen');
        $routePath = array_filter(explode('/', trim($routePath, '/')), 'strlen');

        $success = false;

        foreach ($path as $key => $pathSegment) {
            if (isset($routePath[$key]) && $routePath[$key] == $pathSegment && $this->matchRequest($query)) {
                $success = true;
            } else {
                $success = false;
            }
        }

        return $success ? 'active' : '';
    }

    /**
     * matchRequest
     *
     * @param array $query
     *
     * @return  boolean
     */
    protected function matchRequest($query = [])
    {
        $input = Ioc::getInput();

        if (!$query) {
            return true;
        }

        return !empty(ArrayHelper::query([$input->toArray()], $query));
    }
}
