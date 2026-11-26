# PHP Lint (PHP-CS-Fixer)

## Introduction

Windwalker projects typically use `php-cs-fixer` to check and fix code style.

Projects generally have `friendsofphp/php-cs-fixer` preinstalled, with a `.php-cs-fixer.dist.php` configuration file in the project root. Composer scripts are also typically preconfigured:

```json
"cs-check": "bash bin/php-cs-fixer check --diff",
"cs-fix": "bash bin/php-cs-fixer fix",
```

If these scripts are unavailable, try running `vendor/bin/php-cs-fixer` directly.

LYRASOFT uses PER coding style. For details, see @./php-cs.md

## Usage

```bash
# Check code style
composer cs-check

# OR
php ./vendor/bin/php-cs-fixer check --diff

# -------

# Fix code style
composer cs-fix

# OR
php ./vendor/bin/php-cs-fixer fix
```
