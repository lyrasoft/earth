/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2018 Asikart.
 * @license    MIT
 */

const fusion = require('windwalker-fusion');

// The task `main`
fusion.task('main', () => {
  // Watch start
  fusion.watch('www/asset/css/**/*.scss');
  // Watch end

  // Compile Start
  fusion.sass('www/asset/admin/css/admin.scss');
  // Compile end
});

// The task `bootstrap`
fusion.task('bootstrap', () => {
  // Watch start
  fusion.watch([
    'www/asset/scss/*.scss',
    'www/asset/phoenix/css/bootstrap/**/*.scss',
  ]);
  // Watch end

  // Compile Start
  fusion.sass('www/asset/scss/bootstrap.scss', 'www/asset/css/bootstrap/');
  // Compile end
});

fusion.default(['main']);

/*
 * APIs
 *
 * Compile entry:
 * fusion.js(source, dest, options = {})
 * fusion.babel(source, dest, options = {})
 * fusion.ts(source, dest, options = {})
 * fusion.typeScript(source, dest, options = {})
 * fusion.css(source, dest, options = {})
 * fusion.less(source, dest, options = {})
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
 * fusion.task(name, deps, fn)
 * fusion.watch(glob, opt, fn)
 *
 * Stream Helper:
 * fusion.through(handler) // Same as through2.obj()
 *
 * Config:
 * fusion.disableNotification()
 * fusion.enableNotification()
 */
