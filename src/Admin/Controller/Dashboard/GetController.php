<?php
/**
 * Part of Admin project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Admin\Controller\Dashboard;

use Admin\Model\DashboardModel;
use Admin\View\Dashboard\DashboardHtmlView;
use Phoenix\Controller\Display\DisplayController;
use Windwalker\Core\Repository\Repository;
use Windwalker\Core\View\AbstractView;
use Windwalker\Debugger\Repository\DashboardRepository;

/**
 * The GetController class.
 *
 * @since  1.0
 */
class GetController extends DisplayController
{
    /**
     * Property name.
     *
     * @var  string
     */
    protected $name = 'Dashboard';

    /**
     * Property model.
     *
     * @var  DashboardModel
     */
    protected $model;

    /**
     * Property view.
     *
     * @var  DashboardHtmlView
     */
    protected $view;

    /**
     * A hook before main process executing.
     *
     * @return  void
     * @throws \Exception
     */
    protected function prepareExecute()
    {
        parent::prepareExecute();
    }

    /**
     * Prepare view and default repository.
     *
     * You can configure default model state here, or add more sub models to view.
     * Remember to call parent to make sure default model already set in view.
     *
     * @param AbstractView $view       The view to render page.
     * @param Repository   $repository The default repository.
     *
     * @return  void
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function prepareViewRepository(AbstractView $view, Repository $repository)
    {
        /**
         * @var $view       DashboardHtmlView
         * @var $repository DashboardRepository
         */
        parent::prepareViewRepository($view, $repository);

        // Configure View nad Models here
    }

    /**
     * A hook after main process executing.
     *
     * @param mixed $result The result content to return, can be any value or boolean.
     *
     * @return  mixed
     */
    protected function postExecute($result = null)
    {
        return parent::postExecute($result);
    }
}
