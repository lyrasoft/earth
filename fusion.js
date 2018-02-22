/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

const fusion = require('windwalker-fusion');

fusion.task('main', () => {
  fusion.watch('www/asset/**/*.scss');

  fusion.sass('www/asset/admin/css/admin.scss');
});

fusion.run(['main']);
