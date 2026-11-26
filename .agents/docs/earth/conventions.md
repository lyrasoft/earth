# Earth conventions

- PHP files use `declare(strict_types=1);`, namespaced classes, and the project's PSR-12 code style.
- Keep front-end and admin features in their corresponding route groups and modules under `src/Module/Front/` and `src/Module/Admin/`. Follow the existing module's local `views/` and `assets/` layout for feature-specific code; shared assets belong in `resources/assets/`.
- View models use Windwalker attributes such as `#[ViewModel]` and `#[ViewPrepare]` to declare layouts, assets, and prepared data.
- Add schema changes as timestamp-prefixed migrations under `resources/migrations/`, following neighboring anonymous `AbstractMigration` classes and `#[MigrateUp]` / `#[MigrateDown]` methods.
- `fusionfile.ts` is authoritative for shared asset entry points and build output. Keep TypeScript aliases consistent between `tsconfig.json` and Fusion configuration.
- Edit source assets in `resources/assets/` or a module's `assets/`; do not edit generated output under `www/assets/`.
- Follow [Earth asset and build guidance](assets-and-builds.md) for build order and generated-output safeguards.
- Do not reset or seed a database when its data must be preserved.
