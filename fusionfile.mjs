/**
 * Part of Windwalker Fusion project.
 *
 * @copyright  Copyright (C) 2021 LYRASOFT.
 * @license    MIT
 */

import fusion, { sass, babel, parallel, wait, ts } from '@windwalker-io/fusion';
import { syncModuleScripts, installVendors, findModules } from '@windwalker-io/core';
import path from 'path';

export async function mainCSS() {
  // Watch start
  fusion.watch([
    'resources/assets/scss/front/**/*.scss',
    'src/Module/Front/**/assets/*.scss',
    ...findModules('**/assets/*.scss')
  ]);
  // Watch end

  return wait(
    // Front
    sass(
      [
        'resources/assets/scss/front/main.scss',
        ...findModules('Front/**/assets/*.scss'),
        'src/Module/Front/**/assets/*.scss'
      ],
      'www/assets/css/front/main.css'
    ),
  );
}

export async function adminCSS() {
  // Watch start
  fusion.watch([
    'resources/assets/scss/admin/**/*.scss',
    'src/Module/Admin/**/assets/*.scss',
    ...findModules('**/assets/*.scss')
  ]);
  // Watch end

  return wait(
    // Admin
    sass(
      [
        'resources/assets/scss/admin/main.scss',
        ...findModules('Admin/**/assets/*.scss'),
        'src/Module/Admin/**/assets/*.scss'
      ],
      'www/assets/css/admin/main.css'
    )
  );
}

export async function bootstrap() {
  // Watch start
  fusion.watch('resources/assets/scss/front/_variables.scss');
  // Watch end

  return wait(
    // Front
    sass(
      'resources/assets/scss/front/bootstrap.scss',
      'www/assets/css/front/bootstrap.css'
    )
  );
}

export async function css() {
  return wait(
    // Front
    mainCSS(),
    // Boostrap
    bootstrap(),
    // Admin
    adminCSS(),
  );
}
export async function js() {
  // Watch start
  fusion.watch([
    'resources/assets/src/**/*.{js,mjs,ts}',
    'src/Module/**/assets/**/*.{js,mjs,ts}',
    ...findModules('**/assets/*.{js,mjs,ts}')
  ]);
  // Watch end

  // Compile Start
  return wait(
    babel('resources/assets/src/**/*.{js,mjs}', 'www/assets/js/', { module: 'systemjs' }),
    ts('resources/assets/src/**/*.ts', 'www/assets/js/', { tsconfig: 'tsconfig.js.json' }),
    syncJS()
  );
  // Compile end
}

export async function syncJS() {
  // Compile Start
  return wait(
    ...syncModuleScripts()
  );
  // Compile end
}

export async function images() {
  // Watch start
  fusion.watch('resources/assets/images/**/*');
  // Watch end

  // Compile Start
  return wait(
    fusion.copy('resources/assets/images/**/*', 'www/assets/images/')
  );
  // Compile end
}

export async function install() {
  return installVendors(
    [
      '@fortawesome/fontawesome-free',
      'wowjs',
      'animate.css',
      'jarallax',
    ],
    [
      'lyrasoft/luna'
    ]
  );
}

export default parallel(css, js, images);

/*
 * APIs
 *
 * Compile entry:
 * fusion.js(source, dest, options = {})
 * fusion.babel(source, dest, options = {})
 * fusion.module(source, dest, options = {})
 * fusion.ts(source, dest, options = {})
 * fusion.typeScript(source, dest, options = {})
 * fusion.css(source, dest, options = {})
 * fusion.sass(source, dest, options = {})
 * fusion.copy(source, dest, options = {})
 *
 * Live Reload:
 * fusion.livereload(source, dest, options = {})
 * fusion.reload(file)
 *
 * Gulp proxy:
 * fusion.src(source, options)
 * fusion.dest(path, options)
 * fusion.watch(glob, opt, fn)
 * fusion.symlink(directory, options = {})
 * fusion.lastRun(task, precision)
 * fusion.tree(options = {})
 * fusion.series(...tasks)
 * fusion.parallel(...tasks)
 *
 * Stream Helper:
 * fusion.through(handler) // Same as through2.obj()
 *
 * Config:
 * fusion.disableNotification()
 * fusion.enableNotification()
 */
