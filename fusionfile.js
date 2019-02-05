/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2018 Asikart.
 * @license    MIT
 */

const fusion = require('windwalker-fusion');

// The task `css`
fusion.task('css', () => {
  // Watch start
  fusion.watch('resources/asset/scss/**/*.scss');
  // Watch end

  // Compile Start
  fusion.sass('resources/asset/scss/admin/admin.scss', 'www/asset/admin/css/');
  fusion.sass('resources/asset/scss/front/main.scss', 'www/asset/css/');
  // Compile end
});

// The task `js`
fusion.task('js', () => {
  // Watch start
  fusion.watch('resources/asset/src/admin/**/*.js');
  fusion.watch('resources/asset/src/front/**/*.js');
  // Watch end

  // Compile Start
  fusion.js('resources/asset/src/admin/**/*.js', 'www/asset/admin/js/');
  fusion.js('resources/asset/src/front/**/*.js', 'www/asset/js/');
  // Compile end
});

// The task `bootstrap`
fusion.task('bootstrap', () => {
  // Watch start
  fusion.watch([
    'resources/asset/scss/*.scss'
  ]);
  // Watch end

  // Compile Start
  fusion.sass('resources/asset/scss/bootstrap.scss', 'www/asset/css/bootstrap/');
  // Compile end
});

// The task `install`
fusion.task('install', () => {
  const vendors = [
    //
  ];

  vendors.forEach(vendor => {
    console.log(`[Copy] node_modules/${vendor}/**/* => www/asset/vendor/${vendor}/`);
    fusion.copy(`node_modules/${vendor}/**/*`, `www/asset/vendor/${vendor}/`);
  });
});

fusion.default(['css', 'js']);

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
