# LYRASOFT Package Development Guide

## Git history

- Add a `.gitignore` file. You can use this shared version as a reference: <https://github.com/lyrasoft/luna/blob/master/.gitignore>

## Automatically updating package versions

To make package versions easier to manage, use a prepared release command to update the version and Git tag.

First, create a `VERSION` file in the package root and set its initial version, such as `1.0.0` or `0.1.0`:

- For a feature-complete package ready for a stable release, use `1.0.0`. If it is still being tested, use `1.0.0-beta1`.
- If the package is in early development and breaking changes are still expected, start with `0.1.0`. A `0.x` version indicates that the package is not yet stable and compatibility between versions is not guaranteed.

Next, create a `bin/` directory in the package root. From that directory, download `Console.php` and `release.php`:

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

After updating the code, when preparing a new release, run this from the package root:

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
