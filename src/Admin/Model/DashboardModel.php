<?php
/**
 * Part of Admin project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Admin\Model;

use Admin\Mapper\DashboardMapper;
use Windwalker\Core\Model\DatabaseModel;
use Windwalker\Core\Model\ModelRepository;

/**
 * The DashboardModel class.
 * 
 * @since  1.0
 */
class DashboardModel extends ModelRepository
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'Dashboard';
}
