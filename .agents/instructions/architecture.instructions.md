# Project structure

Earth is the LYRASOFT website host application, built on Windwalker Core 4. The root Composer project maps `App\` to `src/`; editable framework and Lyrasoft packages under `vendor/` are separate repositories with their own boundaries and test setups.

## Directory structure

- [`src/`](../../src/): application PHP code, including shared services and feature modules. `Module/Front/` and `Module/Admin/` separate public and administration features.
- [`routes/`](../../routes/): application, front-end, admin, custom, and installed package route definitions.
- [`etc/`](../../etc/): runtime, application, package, and dependency-injection configuration.
- [`resources/`](../../resources/): source assets, translations, migrations, seeders, menus, and runtime registries.
- [`views/`](../../views/): application layouts, components, and page templates.
- [`www/`](../../www/): web entry points and public/generated assets. Edit asset sources, not generated output under `www/assets/`.
- [`vendor/`](../../vendor/): Composer dependencies, including editable Windwalker and Lyrasoft package repositories. Identify the package targeted before changing package code; follow its own guidance and tests.
- [`tests/`](../../tests/): application-level tests.

The detailed application/runtime relationship, request flow, route loading, and change boundaries are documented in [`.github/docs/architecture.md`](../../.github/docs/architecture.md). Keep that existing guide in place and update it when its detailed architecture information changes.

For application work, follow the routing and package-boundary guidance in [`.github/dev/copilot-instructions.md`](../../.github/dev/copilot-instructions.md). For Windwalker API usage, search the official Windwalker documentation before consulting vendor source.
