<?php
/**
 * Part of Admin project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Admin\Table;

use Lyrasoft\Luna\Table\LunaTable;
use Lyrasoft\Warder\Table\WarderTable;

/**
 * The Table class.
 *
 * @since  1.0
 */
abstract class Table implements WarderTable, LunaTable
{
    const DASHBOARDS = 'dashboards';

    // @muse-placeholder  db-table  Do not remove this.
}
