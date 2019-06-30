<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    __LICENSE__
 */

namespace Lyrasoft\Script;

use Phoenix\Script\BootstrapScript;
use Windwalker\Core\Asset\AbstractScript;
use Windwalker\Utilities\Arr;

/**
 * The EarthScript class.
 *
 * @since  __DEPLOY_VERSION__
 */
class EarthScript extends AbstractScript
{
    /**
     * Run `yarn add @fortawesome/fontawesome-pro` to install fontAwesome pro.
     *
     * Remember to add token to .npmrc
     *
     * @param bool  $pro
     * @param array $options
     *
     * @return  void
     *
     * @since  1.2.10
     */
    public static function fontAwesome(bool $pro = false, array $options = []): void
    {
        if (!static::inited(__METHOD__)) {
            if ($pro) {
                static::getAsset()->alias(
                    'phoenix/css/fontawesome/all.min.css',
                    'vendor/@fortawesome/fontawesome-pro/css/all.min.css'
                )
                    ->alias(
                        'phoenix/css/fontawesome/v4-shims.min.css',
                        'vendor/@fortawesome/fontawesome-pro/css/v4-shims.min.css'
                    );
            }

            // Assets
            BootstrapScript::fontAwesome(
                5,
                Arr::mergeRecursive(['v4shims' => true, 'pro' => $pro], $options)
            );
        }
    }
}
