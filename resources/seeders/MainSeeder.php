<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 {ORGANIZATION}. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

use Windwalker\Core\Seeder\AbstractSeeder;

/**
 * The MainSeeder class.
 *
 * @since  {DEPLOY_VERSION}
 */
class MainSeeder extends AbstractSeeder
{
    /**
     * doExecute
     *
     * @return  void
     * @throws ReflectionException
     * @throws \Windwalker\DI\Exception\DependencyResolutionException
     */
    public function doExecute()
    {
        error_reporting(-1);

        $this->execute(UserSeeder::class);

        $this->execute(ConfigSeeder::class);

        $this->execute(LanguageSeeder::class);

        $this->execute(TagSeeder::class);

        $this->execute(CategorySeeder::class);

        $this->execute(PageSeeder::class);

        $this->execute(ArticleSeeder::class);

        $this->execute(CommentSeeder::class);

        $this->execute(ModuleSeeder::class);

        $this->execute(ContactSeeder::class);

        $this->execute(MenuSeeder::class);
    }

    /**
     * doClear
     *
     * @return  void
     * @throws ReflectionException
     */
    public function doClear()
    {
        $this->clear(UserSeeder::class);

        $this->clear(ConfigSeeder::class);

        $this->clear(LanguageSeeder::class);

        $this->clear(TagSeeder::class);

        $this->clear(CategorySeeder::class);

        $this->clear(PageSeeder::class);

        $this->clear(ArticleSeeder::class);

        $this->clear(CommentSeeder::class);

        $this->clear(ModuleSeeder::class);

        $this->clear(ContactSeeder::class);

        $this->clear(MenuSeeder::class);
    }
}
