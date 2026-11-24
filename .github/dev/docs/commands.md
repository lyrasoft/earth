# Commands and environment

- PHP requires `>=8.4.6` (see the root `composer.json`).
- Install frontend dependencies with `yarn install` (the project pins Yarn 4.13.0).
- `yarn dev` starts Vite; `yarn build` builds development assets; `yarn build:prod` builds production assets; `yarn watch` watches and rebuilds development assets.
- `php windwalker run prepare` installs frontend dependencies and builds assets. `php windwalker run preparedev` also resets migrations and seeds the database; do not use it against data that must be preserved.

## Code style (PHP_CodeSniffer)

CI checks PHP with PHP_CodeSniffer using PSR-12 on `src` and `resources`. Install it globally with
`composer global require squizlabs/php_codesniffer`, then run:

```
$(composer global config bin-dir --absolute)/phpcs src resources -n --extensions=php --standard=PSR12
```

To check one file, pass its path instead of `src resources`.

## Tests

- Earth does not have a test suite or a configured test command yet. Add Earth-owned tests at the repository root only when a test suite is introduced.
- Tests for code inside `vendor/<vendor>/<package>/` (such as `windwalker/core`) belong to that package: put them in the package's own `tests/` directory, use its namespace, `phpunit.xml`, and `vendor/bin/phpunit`, and run them from the package directory. For example, from `vendor/windwalker/core`, run `vendor/bin/phpunit tests/Core/Application`. Do not create root `tests/` or `phpunit.xml` for package tests. Standalone and host app modes for Core, Unicorn and Luna are listed in [vendor-packages](vendor-packages.md#test-modes-all-three-packages).
- For test tiers, see Core's [testing architecture](../../../vendor/windwalker/core/.github/docs/testing-architecture.md). Luna's test setup (MySQL, standalone vs host-app mode) is in [vendor-packages](vendor-packages.md#lyrasoft-luna).
- In development mode, packages under `vendor/` may be newer than the versions in `composer.lock`. When testing or debugging a vendor package, prefer the latest version recorded in its git history over the locked version.
