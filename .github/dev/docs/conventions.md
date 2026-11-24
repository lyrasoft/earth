# Earth conventions

- PHP files use `declare(strict_types=1);`, namespaced classes, and PSR-12 formatting.
- Keep front-end and admin functionality in their respective module (`src/Module/Front`, `src/Module/Admin`) and route groups. Use each module's local `views/` and `assets/` for feature-specific presentation and scripts; use `resources/assets/` for shared assets.
- View models declare layouts/assets with Windwalker attributes such as `#[ViewModel]` and prepare data via `#[ViewPrepare]`.
- Add schema changes as timestamp-prefixed migrations in `resources/migrations/`, following the returned anonymous `AbstractMigration` pattern with `#[MigrateUp]`/`#[MigrateDown]` methods and Windwalker `Schema` helpers.
- `fusionfile.ts` is authoritative for shared asset entry points and build output. TypeScript aliases are mirrored in `tsconfig.json` and the Fusion configuration.
- Edit frontend source under `resources/assets/` or a module's `assets/`, not generated output under `www/assets/`.
- Do not run database-reset or seeding commands against data that must be preserved.
- For Windwalker framework usage and API references, use the public index at https://windwalker.io/llms.txt and fetch only the linked `.md` pages the task needs.
