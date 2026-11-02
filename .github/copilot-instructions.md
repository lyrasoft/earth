# Repository guide

## Commands

- PHP requires `>=8.4.6` (see `composer.json`).
- Install frontend dependencies with `yarn install` (the project pins Yarn 4.13.0).
- `yarn dev` starts Vite; `yarn build` builds development assets; `yarn build:prod` builds production assets; `yarn watch` watches and rebuilds development assets.
- CI checks PHP with PHP_CodeSniffer using PSR-12 on `src` and `resources`. Install it globally with `composer global require squizlabs/php_codesniffer`, then run `$(composer global config bin-dir --absolute)/phpcs src resources -n --extensions=php --standard=PSR12`. To check one file, pass its path instead of `src resources`.
- Earth does not have a test suite or a configured test command yet; Earth's own tests, when added, belong in this repository's root.
- Tests for code inside `vendor/<vendor>/<package>/` (e.g. `windwalker/core`) belong to that package: put them in the package's own `tests/` directory, use the package's namespace, `phpunit.xml` and `vendor/bin/phpunit`, and run them from the package directory, e.g. `cd vendor/windwalker/core && vendor/bin/phpunit tests/Core/Application`. Do not create `tests/` or `phpunit.xml` in the Earth root for them. For choosing a test tier (unit, light integration, app-backed, database), see Core's [`testing-architecture.md`](../vendor/windwalker/core/.github/docs/testing-architecture.md).
- `php windwalker run prepare` installs frontend dependencies and builds assets. `php windwalker run preparedev` also resets migrations and seeds the database; do not use it against data that must be preserved.

## Architecture

- Earth is a Windwalker Core 4 application with Lyrasoft Luna; `windwalker/core` is a separate framework package under `vendor/`, not the app itself. Composer maps the app's `App\` namespace to `src/`; app bootstrap helpers and aliases are loaded from `src/bootstrap.php`.
- `etc/config.php` combines the app configuration, dependency-injection config, and package configs. `routes/main.route.php` loads the front-end, admin, and package route definitions.
- Front-end and admin routes are separate groups (`routes/front.route.php`, `routes/admin.route.php`) with their own middleware and route namespaces. They load local routes, package routes, and `custom/` routes.
- Feature code belongs in `src/Module/`, separated into `Front`, `Admin`, and other app feature directories. A module commonly keeps its controller, view model, Blade views, and assets together. Routes connect handlers and view models; view models declare layouts/assets with Windwalker attributes such as `#[ViewModel]` and prepare data via `#[ViewPrepare]`.
- Shared frontend assets, translations, database migrations, seeders, and runtime registries live under `resources/`. Module assets are also compiled with shared assets by Vite and Windwalker Fusion; `fusionfile.ts` defines the build inputs and outputs to `www/assets/`.
- Framework and package implementations are supplied through Composer under `vendor/`. Application feature changes belong in this repository's `src/`, `routes/`, `etc/`, or `resources/`. Editing is allowed in `vendor/windwalker/*` and `vendor/lyrasoft/*` when the task targets those packages (including their tests and the `src/` changes tests require, such as `@codeCoverageIgnore` tags); work inside the package directory and first read its `AGENTS.md`, `.github/copilot-instructions.md` and test instructions (for `windwalker/core`, `.github/instructions/test*.instructions.md`). Ask the user before editing any other `vendor/` package.
- For the app/package boundary, directory responsibilities, and request flow, see [`.github/docs/architecture.md`](docs/architecture.md); for Core internals (container layers, lifecycle, packages) it links to Core's own [`architecture.md`](../vendor/windwalker/core/.github/docs/architecture.md).

## Conventions

- PHP files use `declare(strict_types=1);`, namespaced classes, and PSR-12 formatting.
- Keep front-end and admin functionality in their respective module and route groups. Use each module's local `views/` and `assets/` for feature-specific presentation and scripts; use `resources/assets/` for shared assets.
- Add schema changes as timestamp-prefixed migrations in `resources/migrations/`, following the returned anonymous `AbstractMigration` pattern with `#[MigrateUp]`/`#[MigrateDown]` methods and Windwalker `Schema` helpers.
- `fusionfile.ts` is the authoritative place for shared asset entry points and build output. TypeScript aliases are mirrored in `tsconfig.json` and the Fusion configuration.

## Documentation links

- Only when listing files or directories as an index, prefer starting each
  item with a Markdown link, followed by its description:
  `[label](relative/path): description`. In ordinary prose or other lists,
  file mentions do not require links; use inline code for paths and identifiers,
  and add links only when useful for navigation.
