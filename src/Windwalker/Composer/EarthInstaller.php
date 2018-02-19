<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Windwalker\Composer;

use Composer\Script\Event;

/**
 * The EarthInstaller class.
 *
 * @since  __DEPLOY_VERSION__
 */
class EarthInstaller
{
    /**
     * install
     *
     * @param Event $event
     *
     * @return  void
     */
    public static function install(Event $event)
    {
        $io = $event->getIO();

        $io->write('Earth will help you run migration, seed and make asset links.', true);

        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            $io->write('(Your system is Windows, please make sure you are running as Administrator.)', true);
        }

        if ($io->askConfirmation("\nDo you want to run 'preparedev'? [Y/n]: ", true)) {
            system('php windwalker run preparedev');
        }

        $io->write('Earth install complete.');
    }
}
