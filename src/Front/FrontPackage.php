<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Front;

use Phoenix\Language\TranslatorHelper;
use Phoenix\Script\BootstrapScript;
use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Router\MainRouter;
use Windwalker\Debugger\Helper\DebuggerHelper;
use Windwalker\Filesystem\Folder;

if (!defined('PACKAGE_FRONT_ROOT')) {
    define('PACKAGE_FRONT_ROOT', __DIR__);
}

/**
 * The FrontPackage class.
 *
 * @since  1.0
 */
class FrontPackage extends AbstractPackage
{
    /**
     * initialise
     *
     * @throws  \LogicException
     * @return  void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * prepareExecute
     *
     * @return  void
     */
    protected function prepareExecute()
    {
        $this->checkAccess();

        // Assets
        Asset::alias('phoenix/css/bootstrap/4/bootstrap.css', 'css/bootstrap/bootstrap.min.css');
        BootstrapScript::css(4);
        BootstrapScript::script(4);
        BootstrapScript::fontAwesome(5);

        // Language
        TranslatorHelper::loadAll($this, 'ini');
    }

    /**
     * checkAccess
     *
     * @return  void
     */
    protected function checkAccess()
    {

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
        if (WINDWALKER_DEBUG) {
            if (class_exists('Windwalker\Debugger\Helper\DebuggerHelper')) {
                DebuggerHelper::addCustomData('Language Orphans',
                    '<pre>' . TranslatorHelper::getFormattedOrphans() . '</pre>');
            }
        }

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
