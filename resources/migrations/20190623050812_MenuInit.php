<?php
/**
 * Part of Admin project.
 *
 * @copyright  Copyright (C) 2014 - 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace -- Ignore migration file

use Lyrasoft\Luna\Admin\Record\CategoryRecord;
use Lyrasoft\Luna\Admin\Record\MenuRecord;
use Lyrasoft\Luna\Table\LunaTable;
use Windwalker\Core\Migration\AbstractMigration;
use Windwalker\Database\Schema\Schema;

/**
 * Migration class of MenuInit.
 */
class MenuInit extends AbstractMigration
{
    /**
     * Migrate Up.
     * @throws Exception
     */
    public function up()
    {
        $this->createTable(LunaTable::MENUS, static function (Schema $schema) {
            $schema->primary('id')->comment('Primary Key');
            $schema->integer('parent_id')->comment('Parent ID');
            $schema->integer('lft')->signed(true)->comment('Left Key');
            $schema->integer('rgt')->signed(true)->comment('Right key');
            $schema->integer('level')->comment('Nested Level');
            $schema->varchar('path')->length(1024)->comment('Alias Path');
            $schema->varchar('type')->length(50)->comment('Content Type');
            $schema->varchar('view')->comment('View Name');
            $schema->varchar('title')->comment('Title');
            $schema->varchar('alias')->comment('Alias');
            $schema->varchar('url')->comment('URL');
            $schema->char('target')->length(10)->comment('Target');
            $schema->text('variables')->comment('Vars');
            $schema->varchar('image')->comment('Main Image');
            $schema->tinyint('state')->signed(true)->comment('0: unpublished, 1:published');
            $schema->datetime('created')->comment('Created Date');
            $schema->integer('created_by')->comment('Author');
            $schema->datetime('modified')->comment('Modified Date');
            $schema->integer('modified_by')->comment('Modified User');
            $schema->char('language')->length(7)->comment('Language');
            $schema->text('params')->comment('Params');

            $schema->addIndex('alias(150)');
            $schema->addIndex('path(150)');
            $schema->addIndex('view(150)');
            $schema->addIndex('type(50)');
            $schema->addIndex(['lft', 'rgt']);
            $schema->addIndex('language');
            $schema->addIndex('created_by');
        });

        $record = new MenuRecord();
        $record->createRoot();
    }

    /**
     * Migrate Down.
     * @throws Exception
     */
    public function down()
    {
        $this->drop(LunaTable::MENUS);

        $record = new CategoryRecord();
        $record->createRoot();
    }
}
