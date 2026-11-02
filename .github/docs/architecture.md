# Earth app and Windwalker Core architecture

## Relationship between the app and the package

Earth is the application in this repository. It requires `windwalker/core` and
other Windwalker and Lyrasoft packages through the root
[`composer.json`](../../composer.json). Composer maps the application's `App\`
namespace to the root [`src/`](../../src/) directory and loads
[`src/bootstrap.php`](../../src/bootstrap.php).

[`vendor/windwalker/core`](../../vendor/windwalker/core/) is a separate
Composer package and Git repository. Its Composer configuration maps
`Windwalker\` to that package's [`src/`](../../vendor/windwalker/core/src/)
directory and loads its own
[`src/bootstrap.php`](../../vendor/windwalker/core/src/bootstrap.php). Core
provides shared framework capabilities, including application/runtime
services, routing, middleware, console commands, database and migration
support, rendering, and package integration. Earth uses those capabilities;
Earth-specific features belong in the app, not in the framework package. Make
changes under [`vendor/windwalker/core`](../../vendor/windwalker/core/) (and
other `vendor/windwalker/*` or `vendor/lyrasoft/*` packages) only when the task
is specifically about that package, including its tests, and treat them as
changes to a separate package with its own `AGENTS.md` and test setup. Ask the
user before editing any other `vendor/` package.

## Windwalker Core package layout

- [`vendor/windwalker/core/src/Core/`](../../vendor/windwalker/core/src/Core/):
  the main framework implementation, organized into areas such as
  `Application/`, `Router/`, `Middleware/`, `Database/`, `Migration/`,
  `Renderer/`, `Package/`, and `Console/`.
- [`vendor/windwalker/core/src/Console/`](../../vendor/windwalker/core/src/Console/)
  and
  [`vendor/windwalker/core/src/WebSocket/`](../../vendor/windwalker/core/src/WebSocket/):
  additional console and WebSocket framework components.
- [`vendor/windwalker/core/src/Debugger/`](../../vendor/windwalker/core/src/Debugger/):
  debugger package and supporting services.
- [`vendor/windwalker/core/etc/`](../../vendor/windwalker/core/etc/),
  [`routes/`](../../vendor/windwalker/core/routes/),
  [`resources/`](../../vendor/windwalker/core/resources/), and
  [`views/`](../../vendor/windwalker/core/views/):
  package-level configuration, routes, resources, and views.
- [`vendor/windwalker/core/tests/`](../../vendor/windwalker/core/tests/):
  package test support and tests. The package uses its own
  [`composer.json`](../../vendor/windwalker/core/composer.json), autoload
  configuration, and validation commands.

## How Earth plugs into Core

Core's full description is in
[`vendor/windwalker/core/.github/docs/architecture.md`](../../vendor/windwalker/core/.github/docs/architecture.md);
only the points Earth code depends on are summarized here.

- Entry points: [`www/index.php`](../../www/index.php) (web) and
  [`windwalker`](../../windwalker) (CLI) boot `Core\Runtime\Runtime`, load
  [`etc/runtime.config.php`](../../etc/runtime.config.php), and resolve the
  apps from its `factories`. That file maps the `@root`, `@etc`, `@resources`
  ... path aliases and builds `App\Web\Application` (extends Core's
  `WebApplication`, configured by `etc/app/main.config.php`) and
  `App\Console\Application`.
- Config: `etc/app/*.config.php` select the app's `middlewares`, `listeners`,
  and `commands`; [`etc/config.php`](../../etc/config.php) supplies the
  shared sections and `etc/packages/*.config.php`. Core registers each section's
  providers, bindings, and listeners.
- Container layers: runtime, app, and per-request. Request-scoped services
  (request, `AppContext`, `AppState`) are not shared across requests.
- Request path: Core middleware chain → `RoutingMiddleware` → route-level
  middleware (`front`/`admin` groups) → `ControllerDispatcher` → controller →
  `#[ViewModel]` class rendering a Blade/Edge layout. Controller methods default
  to `index` for GET and `save` for POST/PUT/PATCH, `delete` for DELETE.
- Packages: Lyrasoft/Windwalker packages declare
  `extra.windwalker.packages` in their `composer.json`; Core discovers them and
  installs config, routes, migrations, and assets into Earth with
  `php windwalker pkg:install`. Package routes are loaded from
  `routes/packages/` and `etc/packages/`.

## Earth application layout

- [`src/`](../../src/): app PHP code. Shared app classes live alongside
  [`src/Module/`](../../src/Module/), which
  contains feature code. Front-end and admin modules are separated under
  [`src/Module/Front/`](../../src/Module/Front/) and
  [`src/Module/Admin/`](../../src/Module/Admin/);
  [`src/Module/Flower/`](../../src/Module/Flower/) holds
  flower-related code shared outside those two areas.
- [`routes/`](../../routes/): route registration.
  [`routes/main.route.php`](../../routes/main.route.php) loads the front-end,
  admin, and package routes. [`routes/front.route.php`](../../routes/front.route.php)
  and [`routes/admin.route.php`](../../routes/admin.route.php) establish
  separate namespaces and middleware; local, package, and custom routes are
  loaded from their respective directories.
- [`etc/`](../../etc/): application, framework, dependency-injection, and
  package configuration. [`etc/config.php`](../../etc/config.php) combines
  app-level configuration and package configuration files under
  [`etc/packages/`](../../etc/packages/).
- [`resources/`](../../resources/): app-owned shared assets, translations,
  database migrations, seeders, menus, and runtime registries. Keep
  feature-specific presentation and scripts with their module where that is
  the existing pattern.
- [`fusionfile.ts`](../../fusionfile.ts), [`vite.config.ts`](../../vite.config.ts),
  and [`tsconfig.json`](../../tsconfig.json): frontend asset
  compilation inputs, output, and TypeScript configuration. Fusion builds
  shared assets from [`resources/assets/`](../../resources/assets/) and module
  assets; the configured
  output directory is [`www/assets/`](../../www/assets/).
- [`views/`](../../views/): app-level views and layouts.
- [`www/`](../../www/): web-facing entry points and compiled/public assets. Treat generated
  asset output as build output; edit the source assets and build configuration
  instead.
- [`windwalker`](../../windwalker): the app's Windwalker CLI entry point.

## Request and feature flow

1. [`routes/main.route.php`](../../routes/main.route.php) builds the web route
   group and loads the front-end, admin, and package route definitions.
2. [`routes/front.route.php`](../../routes/front.route.php) and
   [`routes/admin.route.php`](../../routes/admin.route.php) add their own
   middleware and load local feature routes, package routes, and custom routes.
3. A feature route selects its handler and, where applicable, its view model.
   Feature code is organized under
   [`src/Module/Front/`](../../src/Module/Front/) or
   [`src/Module/Admin/`](../../src/Module/Admin/); follow neighboring modules
   for the exact controller, view, form, and asset layout.
4. Shared configuration is assembled through
   [`etc/config.php`](../../etc/config.php); package settings are kept in
   [`etc/packages/`](../../etc/packages/).
5. Database structure and initial data are maintained in
   [`resources/migrations/`](../../resources/migrations/) and
   [`resources/seeders/`](../../resources/seeders/). Shared language and asset
   sources are also under `resources/`.

## Where to make changes

- For Earth behavior, edit the app's [`src/`](../../src/),
  [`routes/`](../../routes/), [`etc/`](../../etc/),
  [`resources/`](../../resources/), or [`views/`](../../views/), following
  the owning feature's current organization.
- For shared framework behavior, work in the separate `windwalker/core`
  package only when the task explicitly calls for a framework change.
- For frontend behavior, edit source files under
  [`resources/assets/`](../../resources/assets/) or a module's `assets/`, and
  update [`fusionfile.ts`](../../fusionfile.ts) when build inputs or outputs
  change. Do not edit generated files under [`www/assets/`](../../www/assets/)
  as source.
- For schema or seed-data changes, use the app's migration and seeder patterns;
  do not run database-reset or seed commands against data that must be kept.

## Documentation links

- Only when listing files or directories as an index, prefer starting each
  item with a Markdown link, followed by its description:
  `[label](relative/path): description`. In ordinary prose or other lists,
  file mentions do not require links; use inline code for paths and identifiers,
  and add links only when useful for navigation.
