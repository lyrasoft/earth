<?php
/**
 * Part of Admin project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Admin;

use Lyrasoft\Warder\Warder;
use Phoenix\Script\BootstrapScript;
use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Router\MainRouter;
use Windwalker\Filesystem\Folder;

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
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \ReflectionException
     */
    protected function prepareExecute()
    {
        $this->checkAccess();

        // Assets
        BootstrapScript::css(4);
        BootstrapScript::fontAwesome(5);
        Asset::addCSS($this->name . '/css/admin.min.css');

        BootstrapScript::script(4);
        Asset::addJS($this->name . '/js/admin.min.js');

        // Language
        Translator::loadAll($this, 'ini');
        Translator::loadAll();
    }

    /**
     * checkAccess
     *
     * @return  void
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function checkAccess()
    {
        // Add your access checking
        if (Warder::requireLogin(true)) {
            Warder::goToLogin($this->app->uri->full);
        }

        if (Warder::isLogin() && !Warder::getUser()->getGroupProperties()['is_admin']) {
            $this->app->redirect($this->router->to('front@home'));
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
