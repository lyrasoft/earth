# Earth development instructions

## Purpose and entry points

This directory contains guidance for developing Earth and its editable framework collaboration packages. The root
[`AGENTS.md`](../../AGENTS.md) currently directs contributors to both instruction entry points:

- [Host guidance](../copilot-instructions.md) is the concise, general-purpose layer intended for user extensions.
- This file covers Earth-specific development, architecture, and package boundaries.

Use the indexes in [instructions](instructions/README.md), [skills](skills/index.md), and [docs](docs/index.md) to find
topic-specific guidance. Keep this entry point focused on durable rules and link to longer references rather than
duplicating them.

## Commands

- PHP requires `>=8.4.6` (see the root `composer.json`).
- Install frontend dependencies with `yarn install` (the project pins Yarn 4.13.0).
- `yarn dev` starts Vite; `yarn build` builds development assets; `yarn build:prod` builds production assets; `yarn watch` watches and rebuilds development assets.
- CI checks PHP with PHP_CodeSniffer using PSR-12 on `src` and `resources`. Install it globally with `composer global require squizlabs/php_codesniffer`, then run `$(composer global config bin-dir --absolute)/phpcs src resources -n --extensions=php --standard=PSR12`. To check one file, pass its path instead of `src resources`.
- Earth does not have a test suite or a configured test command yet. Add Earth-owned tests at the repository root only when a test suite is introduced.
- Tests for code inside `vendor/<vendor>/<package>/` (such as `windwalker/core`) belong to that package: put them in the package's own `tests/` directory, use its namespace, `phpunit.xml`, and `vendor/bin/phpunit`, and run them from the package directory. For example, from `vendor/windwalker/core`, run `vendor/bin/phpunit tests/Core/Application`. Do not create root `tests/` or `phpunit.xml` for package tests. For test tiers, see Core's [testing architecture](../../vendor/windwalker/core/.github/docs/testing-architecture.md).
- In development mode, packages under `vendor/` may be newer than the versions in `composer.lock`. When testing or debugging a vendor package, prefer the latest version recorded in its git history over the locked version.
- `php windwalker run prepare` installs frontend dependencies and builds assets. `php windwalker run preparedev` also resets migrations and seeds the database; do not use it against data that must be preserved.

## Architecture

- Earth is a Windwalker Core 4 application with Lyrasoft Luna. Composer maps the app's `App\` namespace to `src/`; app bootstrap helpers and aliases are loaded from `src/bootstrap.php`.
- `etc/config.php` combines app configuration, dependency-injection configuration, and package configurations. `routes/main.route.php` loads front-end, admin, and package route definitions.
- Front-end and admin routes are separate groups (`routes/front.route.php`, `routes/admin.route.php`) with their own middleware and route namespaces. They load local, package, and `custom/` routes.
- Feature code belongs in `src/Module/`, separated into `Front`, `Admin`, and other app feature directories. A module commonly keeps its controller, view model, Blade views, and assets together. Routes connect handlers and view models; view models declare layouts/assets with Windwalker attributes such as `#[ViewModel]` and prepare data via `#[ViewPrepare]`.
- Shared frontend assets, translations, database migrations, seeders, and runtime registries live under `resources/`. Module assets are also compiled with shared assets by Vite and Windwalker Fusion; `fusionfile.ts` defines build inputs and outputs in `www/assets/`.
- `vendor/` contains separate Composer packages, not Earth application source. Make Earth-specific changes in the app. Framework and package changes are in scope when a task targets those packages; follow the package's own `AGENTS.md`, `.github/copilot-instructions.md`, and test instructions first. Ask the user before editing any other `vendor/` package.
- For the app/package boundary, directory responsibilities, and request flow, see [the architecture guide](../docs/architecture.md). It links to Core's architecture guide for framework internals.

## Conventions

- PHP files use `declare(strict_types=1);`, namespaced classes, and PSR-12 formatting.
- Keep front-end and admin functionality in their respective module and route groups. Use each module's local `views/` and `assets/` for feature-specific presentation and scripts; use `resources/assets/` for shared assets.
- Add schema changes as timestamp-prefixed migrations in `resources/migrations/`, following the returned anonymous `AbstractMigration` pattern with `#[MigrateUp]`/`#[MigrateDown]` methods and Windwalker `Schema` helpers.
- `fusionfile.ts` is authoritative for shared asset entry points and build output. TypeScript aliases are mirrored in `tsconfig.json` and the Fusion configuration.
- For the Vite/Fusion build flow, Core processors, and PHP runtime asset resolution, follow the [asset instructions](instructions/fusion-assets.instructions.md). For Unicorn `*Script` helpers and the `@windwalker-io/unicorn-next` runtime, see [unicorn-scripts](instructions/unicorn-scripts.instructions.md).
- Before any frontend or browser (e2e) verification, read [instructions/README.md](instructions/README.md) and the topic files it lists (assets, unicorn-scripts, e2e-browser-test) first. Do not guess the site URL, login, or build flow; follow those files, and stop to ask the user when they say to.
- Edit frontend source files under `resources/assets/` or a module's `assets/`, not generated output under `www/assets/`.
- Do not run database-reset or seeding commands against data that must be preserved.
- Do not inject the whole application only to read the debug flag or app secret. Inject `Windwalker\Core\Application\AppEnvironmentInterface` (`isDebug()`, `getMode()`, `getName()`, `getVerbosity()`, `isVerbose()`, `isCli()`) and `SecretKeeperInterface` (`getSecret(?string $deriveFor = null)`) instead. Similarly, prefer `PathResolver`, `Navigator`, `AppRequest`, `SystemUri`, and `MaintenanceManager` over the matching `$app->...()` helpers.
- `AppProvider` registers the config-based `AppEnvironment` and `SecretKeeper` through `AppEnvironmentProvider`, which skips an interface that is already registered. To override them with an implementation that does not read config, register your own for the interface before the app boots. In unit tests, build `new AppEnvironment(new Config([...]))` directly. A test app factory that does not boot (for example `tests/Core/Application/Stub/TestWebAppFactory.php` in Core) must register `AppEnvironmentProvider` itself once a class injects these interfaces.

## Documentation links

- Only when listing files or directories as an index, prefer starting each item with a Markdown link followed by its description: `[label](relative/path): description`.
- In ordinary prose or other lists, use inline code for paths and identifiers; add links only when useful for navigation.
