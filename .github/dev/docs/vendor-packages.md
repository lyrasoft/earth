# Packages developed from this host app

Earth is a host app: `vendor/windwalker/core`, `vendor/windwalker/unicorn`, and `vendor/lyrasoft/luna` are editable
packages, each its own git repository (own `.git`). Before editing, identify which one the task targets from the file
paths, then follow that package's rules. Commit changes in the package repository, not in Earth. Package edits are
overwritten by `composer update` unless upstreamed.

Path rule: the first matching prefix decides the package, and every package has its own `AGENTS.md` and
`.github/copilot-instructions.md` at its root. Anything under `vendor/` outside these three paths is not Earth source.

- `vendor/windwalker/core/` -> Core
- `vendor/windwalker/unicorn/` -> Unicorn
- `vendor/lyrasoft/luna/` -> Luna
- everything else (`src/`, `routes/`, `etc/`, `resources/`) -> Earth

| Working in | Package | Own guidance | Tests |
| --- | --- | --- | --- |
| Earth app (`src/`, `routes/`, `etc/`, `resources/`) | Earth | [dev instructions](../copilot-instructions.md) | none yet (see [commands](commands.md)) |
| `vendor/windwalker/core` | Windwalker Core (framework) | its `AGENTS.md`, `.github/copilot-instructions.md`, [architecture](../../../vendor/windwalker/core/.github/docs/architecture.md) | PHPUnit in the package: `vendor/bin/phpunit tests/...` from the package dir |
| `vendor/windwalker/unicorn` | Unicorn (RAD layer: admin/CRUD, scripts, `@windwalker-io/unicorn-next`) | its `AGENTS.md`, `.github/copilot-instructions.md`, `docs/` | PHPUnit in the package, same as Core |
| `vendor/lyrasoft/luna` | Lyrasoft Luna (CMS features) | its `AGENTS.md`, `.github/copilot-instructions.md` | PHPUnit in the package (needs MySQL): `vendor/bin/phpunit` from the package dir; see [Luna tests](#lyrasoft-luna) |

Any other `vendor/` package is not Earth source: ask the user before editing it.

## Test modes (all three packages)

Every package has the same two modes. Run from the package directory (`cd vendor/<vendor>/<package>`). Standalone is the default; use host app mode only when the test needs Earth's autoloader, Runtime or config.

| Mode | Config | Command | Notes |
| --- | --- | --- | --- |
| Standalone (default) | `phpunit.dist.xml`; Core and Unicorn read a local `phpunit.xml` copied from it | `composer install`, then `vendor/bin/phpunit` | Uses the package's own `vendor/`. Core and Unicorn need `phpunit.xml` created first. |
| Host app | `phpunit.app.dist.xml`; copy to `phpunit.app.xml` | `../../bin/phpunit -c phpunit.app.xml` (Luna also `composer test:app`) | Uses Earth's `vendor/`; `WINDWALKER_TEST_APP_ROOT` points to Earth. |
| CI | `phpunit.ci.xml` | `vendor/bin/phpunit -c phpunit.ci.xml` | Used by CI only. |

- `phpunit.xml` and `phpunit.app.xml` are local, git-ignored copies. Never commit them or put DSN credentials in the `*.dist.xml` files.
- Each package's `tests/bootstrap.app.php` registers its own TestApp with `TestAppRegistry::use(...)`.
- Database tests drop and recreate the DB from `WINDWALKER_TEST_DB_DSN`: use a disposable database, never Earth's real one.
- Core and Unicorn support several DB drivers via `WINDWALKER_TEST_DB_DSN_*`; Luna only needs MySQL.

## Windwalker Core and Unicorn

- Read the package's own `AGENTS.md` and `.github/copilot-instructions.md` first; they override Earth rules for that package.
- Put tests in the package's `tests/`, use its namespace and `phpunit.xml`, and run from the package directory. Do not create root `tests/` or `phpunit.xml` in Earth. See [commands](commands.md) and the [test skill](../skills/test/SKILL.md).
- Unicorn JS/PHP script work: [unicorn-scripts](../instructions/unicorn-scripts.instructions.md), [php-js-script-pipeline](php-js-script-pipeline.md).
- Locked versions in `composer.lock` may be older than the checked-out package; prefer the latest in the package's git history.

## Lyrasoft Luna

- PHP lives in `src/` (for example `src/Captcha/`, `src/Script/`), frontend source in `assets/src/` (for example `assets/src/modules/captcha.ts`), and built output in `assets/dist/`.
- Build frontend assets from `vendor/lyrasoft/luna/assets` with `yarn build` (regenerates `dist/chunks/*.js`, for example `captcha.js`); `yarn build:assets` does not regenerate those chunks.
- Tests live in `tests/` (namespace `Lyrasoft\Luna\Tests`) and use the real MySQL database from `WINDWALKER_TEST_DB_DSN` (default `luna_test`, prefix `luna_`) plus Luna's own `resources/migrations`. The database is dropped and recreated each run, so never point it at Earth's real database. No other DB is needed.
- Two modes, both run from `vendor/lyrasoft/luna`:
  - Standalone (default): `composer install`, then `vendor/bin/phpunit` (`phpunit.dist.xml`).
  - Host app: copy `phpunit.app.dist.xml` to `phpunit.app.xml` (git-ignored), then run Earth's `../../bin/phpunit -c phpunit.app.xml` (or `composer test:app`). It sets `WINDWALKER_TEST_APP_ROOT` so Earth's autoloader and Runtime are used.
- `tests/bootstrap.app.php` registers the app with `TestAppRegistry::use(...)`; Core's `TestDatabaseFixtures` and `ServiceAwareTestTrait` resolve it from there. Standalone mode needs a Core version that includes `TestAppRegistry`.
- Use `Windwalker\Core\Testing\Traits\DatabaseTestTrait` for DB tests and `TestDatabaseFixtures::migrate()` in `prepareTablesBeforeClass()` to create the schema.
