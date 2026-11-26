# Windwalker Package Development Basics

A Windwalker Package is a Composer package (with `type` set to `windwalker-package`) that can install reusable parts—such as MVC classes, entities, routes, migrations, and configuration files—into a host application.

For complete details, see the official documentation: https://windwalker.io/documentation/framework/packages/package-development.md

## Basic structure

```
acme/portfolio
├── composer.json
├── README.md
├── resources/          # Files copied by pkg:install
│   ├── config/
│   ├── routes/
│   ├── migrations/
│   └── languages/
└── src/
    └── PortfolioPackage.php
```

## composer.json

- Set `type` to `windwalker-package`.
- Declare the namespace in `autoload.psr-4`.
- Register the Package class in `extra.windwalker.packages`.

```json
{
    "name": "acme/portfolio",
    "type": "windwalker-package",
    "autoload": {
        "psr-4": { "Acme\\Portfolio\\": "src/" }
    },
    "extra": {
        "windwalker": {
            "packages": ["Acme\\Portfolio\\PortfolioPackage"]
        }
    }
}
```

## Package class

- Extend `Windwalker\Core\Package\AbstractPackage`.
- Implement `install(PackageInstaller $installer): void` and call installer methods to copy resources.
- Build resource paths with `static::path()`.

```php
class PortfolioPackage extends AbstractPackage
{
    public function install(PackageInstaller $installer): void
    {
        $installer->installConfig(static::path('resources/config/*.php'), 'config');
        $installer->installRoutes(static::path('resources/routes/*.php'), 'routes');
    }
}
```

Common installer methods include `installConfig()`, `installRoutes()`, `installMigrations()`, `installLanguages()`, `installViews()`, `installSeeders()`, `installFiles()`, `installModules()`, and `installMVCModules()`. Each method can accept a tag so users can install only some resources. See the official documentation for method parameters and details.

## Developing in a host application

1. Add a `path` repository to the host application's `composer.json` pointing to the local Package directory, then `require` the package.
2. Run `composer update acme/portfolio`.
3. Run `php windwalker pkg:install acme/portfolio --all` to install resources.
4. After modifying files copied by `pkg:install`, reinstall them. Passing `--force` overwrites existing files, so commit the host application before running it.

### Starting a new project from the host app

When a user gives the initial development instruction, first ask for the **package name and namespace**, then ask whether they want it in an external directory or inside `vendor/`.

If they want it inside `vendor/`, it can initially be placed in the project at `vendor/{namespace}/{name}`. If they want an external directory, store it in the directory they specify.

Then add a `path` repository to `composer.json` pointing to that directory, and require the package by name.

## Notes

- Do not put plaintext keys or passwords in configuration files; read them with `env()`.
- `installModules()` reads and writes files as text; use `installFiles()` for binary files (images, fonts).
- Run `git init` early in development and commit regularly to make it easier to revert failed changes.

## Related documentation

- Configuration files and `ConfigModule`: <https://windwalker.io/documentation/framework/getting-started/configuration.md>
- Package Migration: <https://windwalker.io/documentation/framework/packages/package-migration.md>
- Package JavaScript: <https://windwalker.io/documentation/framework/packages/package-javascript.md>
- To modify an existing Package or Framework source (`composer install --prefer-source`), see the “Modifying an Existing Package” section in the official documentation.
