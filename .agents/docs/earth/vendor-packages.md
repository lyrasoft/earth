# Earth hosted-package boundaries and tests

Earth is the host application. The following directories are separate Git repositories with their own contributor instructions and tests:

| Path | Package | Test location / command |
| --- | --- | --- |
| `vendor/windwalker/core/` | Windwalker Core | Package `tests/`; run `vendor/bin/phpunit` from the package directory, or use host-app mode |
| `vendor/windwalker/unicorn/` | Windwalker Unicorn | Package `tests/`; run `vendor/bin/phpunit` from the package directory, or use host-app mode |
| `vendor/lyrasoft/luna/` | Lyrasoft Luna | Package `tests/`; run `vendor/bin/phpunit` from the package directory, or use host-app mode |
| `src/`, `routes/`, `etc/`, `resources/`, `views/` | Earth | Earth currently has no root PHPUnit suite |

## Before editing

1. Classify the target by its path. Anything in `vendor/` outside the three packages above is not Earth source; ask before editing it.
2. Read the target package's `AGENTS.md` and `.github/copilot-instructions.md`. Those rules, test setup, and Git repository take precedence for package changes.
3. Make and commit package changes in that package's repository. Earth-side changes belong to the root repository. Package edits may be overwritten by `composer update` until they are upstreamed.

## Test modes

Run package tests from the package directory. Use the package's own configuration and namespace; do not create an Earth-root `phpunit.xml` for package tests.

| Mode | Configuration | Command |
| --- | --- | --- |
| Standalone | `phpunit.dist.xml`; Core and Unicorn may require a local `phpunit.xml` copied from it | `composer install`, then `vendor/bin/phpunit` |
| Host app | `phpunit.app.dist.xml`, copied to the ignored `phpunit.app.xml` | From the package directory: `../../bin/phpunit -c phpunit.app.xml` |
| CI | `phpunit.ci.xml` | `vendor/bin/phpunit -c phpunit.ci.xml` |

Do not commit local `phpunit.xml` / `phpunit.app.xml` files or put DSN credentials in `*.dist.xml`. Host-app mode uses Earth's Composer autoloader and sets `WINDWALKER_TEST_APP_ROOT`; use the package's own guidance to determine whether a suite requires standalone or host-app mode.

## Database safety

- Database tests can drop and recreate their configured database. Use a disposable test database, never Earth's real, shared, staging, or production database.
- Configure test connections only through the test environment expected by that package (commonly `WINDWALKER_TEST_DB_DSN`). Do not expose credentials in documentation or logs.
- Core and Unicorn support multiple DB drivers; Luna's database tests require MySQL. Check the package's own test instructions for current requirements.
- Do not run database test suites in parallel against the same database.

## Package-specific notes

- Core and Unicorn: read their own instructions first; their checked-out source may be newer than the version locked in Earth's `composer.lock`.
- Unicorn PHP Script tests belong in `vendor/windwalker/unicorn/tests/Script/`. For Unicorn JS/PHP collaboration, see [Unicorn JS development](unicorn-js-development.md).
- Luna frontend source is under `vendor/lyrasoft/luna/assets/src/`; built output is under that package's `assets/dist/`. Build using the package's own instructions. Its database tests use its own migrations and a disposable MySQL database.

The legacy file at `.github/dev/docs/vendor-packages.md` remains for existing links; this `.agents` reference is the canonical routing document for agents.
