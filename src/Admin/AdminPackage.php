<?php
/**
 * Part of Admin project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Admin;

use Lyrasoft\Warder\Helper\UserHelper;
use Phoenix\Language\TranslatorHelper;
use Phoenix\Script\BootstrapScript;
use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Router\MainRouter;
use Windwalker\Filesystem\Folder;
use Windwalker\Router\Exception\RouteNotFoundException;

if (!defined('PACKAGE_ADMIN_ROOT')) {
    define('PACKAGE_ADMIN_ROOT', __DIR__);
}

/**
 * The AdminPackage class.
 *
 * @since  1.0
 */
class AdminPackage extends AbstractPackage
{
    /**
     * initialise
     *
     * @return  void
     * @throws \ReflectionException
     * @throws \Windwalker\DI\Exception\DependencyResolutionException
     */
    public function boot()
    {
        parent::boot();

        // Add your own boot logic
    }

    /**
     * prepareExecute
     *
     * @return  void
     * @throws \Exception
     */
    protected function prepareExecute()
    {
        $this->checkAccess();

        // Assets
        BootstrapScript::css(4);
        BootstrapScript::script(4);
        BootstrapScript::fontAwesome(5);
        Asset::addCSS($this->name . '/css/admin.css');

        // Language
        TranslatorHelper::loadAll($this, 'ini');
    }

    /**
     * checkAccess
     *
     * @return  void
     *
     * @throws  RouteNotFoundException
     * @throws  \Exception
     */
    protected function checkAccess()
    {
        // Add your access checking
        if (!UserHelper::authorise() /* && User::get()->group == 2 */) {
            UserHelper::goToLogin($this->app->uri->full);
        }
    }

    /**
     * postExecute
     *
     * @param string $result
     *
     * @return  string
     */
    protected function postExecute($result = null)
    {
        return $result;
    }

    /**
     * loadRouting
     *
     * @param MainRouter $router
     * @param string     $group
     *
     * @return MainRouter
     */
    public function loadRouting(MainRouter $router, $group = null)
    {
        $router = parent::loadRouting($router, $group);

        $router->group($group, function (MainRouter $router) {
            $router->addRouteFromFiles(Folder::files(__DIR__ . '/Resources/routing'), $this->getName());

            // Merge other routes here...
        });

        return $router;
    }
}
