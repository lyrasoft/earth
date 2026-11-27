# LYRASOFT Package Development Guide

For general package development guidance, see `../../../windwalker/references/development-core/package-development.md`.

This document covers additional procedures for LYRASOFT packages. Follow the standard workflow above first, then apply the LYRASOFT-specific development setup described here.

## Development files in the package root

- `.gitignore`: Use this shared version as a reference: <https://raw.githubusercontent.com/lyrasoft/luna/refs/heads/master/.gitignore>, and adjust as needed.
- `.gitattributes`: Use the template at `../../assets/.gitattributes` and adjust it as needed.
- `README.md`: You can start with the package title.
- `VERSION`: Start with `0.1.0`.

## PHP-CS-Fixer and Git Hook

Packages can use PHP-CS-Fixer to check and fix code, and optionally enable a pre-commit hook to format staged PHP files. Copy these templates to the package root:

| Template | Location in package |
| --- | --- |
| [`.php-cs-fixer.dist.php`](../../assets/cs-fixer/.php-cs-fixer.dist.php) | `.php-cs-fixer.dist.php` |
| [`autoload.app.php`](../../assets/cs-fixer/autoload.app.php) | `bin/autoload.app.php` |
| [`cs-fixer.php`](../../assets/cs-fixer/cs-fixer.php) | `bin/cs-fixer.php` |
| [`pre-commit`](../../assets/cs-fixer/pre-commit) | `.githooks/pre-commit` |

PHP-CS-Fixer uses Windwalker Core's shared `.php-cs-fixer.rules.php` rules, loading them from `../core/` or `vendor/windwalker/core/`, in that order.

Add these scripts to the package's `composer.json`:

```json
{
    "scripts": {
        "cs-check": "bin/cs-fixer.php check --diff",
        "cs-fix": "bin/cs-fixer.php fix",
        "prepare-hooks": "git config core.hooksPath .githooks",
        "remove-hooks": "git config --local --unset core.hooksPath"
    }
}
```

Run `composer cs-check` to check the code, or `composer cs-fix` to fix it. You can pass PHP-CS-Fixer arguments, for example:

```shell
composer cs-check -- --path-mode=intersection src/SomeClass.php
composer cs-fix -- --path-mode=intersection src/SomeClass.php
```

- `bin/autoload.app.php` loads the Composer autoloader for package development commands and tests. Production applications should load their own `vendor/autoload.php`.
- `bin/cs-fixer.php` loads the autoloader and runs PHP-CS-Fixer. Keep this file executable.
- `.githooks/pre-commit` processes staged PHP files that are added, copied, modified, or renamed. If there are unstaged changes, it does not re-stage the file. The commit fails if the hook or PHP-CS-Fixer fails.

After setup, remind the user to run `composer prepare-hooks` in the package root to enable the hook.

## Automatically updating package versions

Use the prepared release command to update the package version and Git tag.

First, create a `VERSION` file in the package root and set its initial version, such as `0.1.0`.

Next, create a `bin/` directory in the package root, and download `Console.php` and `release.php` into it:

```shell
wget https://raw.githubusercontent.com/asika32764/php-simple-console/master/src/Console.php
wget https://raw.githubusercontent.com/asika32764/php-simple-console/refs/heads/master/bin/release.php

chmod +x release.php
```

Alternatively, use `curl`:

```shell
curl https://raw.githubusercontent.com/asika32764/php-simple-console/master/src/Console.php -o Console.php
curl https://raw.githubusercontent.com/asika32764/php-simple-console/refs/heads/master/bin/release.php -o release.php

chmod +x release.php
```

### Version update commands

After updating the code, run this from the package root when preparing a release:

```shell
php bin/release.php patch
```

Other options:

```shell
php bin/release.php -h       # Show help
php bin/release.php patch    # Bump the patch version
php bin/release.php minor    # Bump the minor version
php bin/release.php major    # Bump the major version
php bin/release.php x.x.x    # Set a specific version
```
