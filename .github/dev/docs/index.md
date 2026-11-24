# Development documentation

Use the repository's [Earth and Windwalker Core architecture guide](../../docs/architecture.md) for the application/package
boundary, directory responsibilities, request flow, and guidance on where to make changes.

Add detailed, durable development references here when they need more context than the concise rules in
[the development instructions](../copilot-instructions.md). Keep this index updated when adding a new reference.

- [Commands and environment](commands.md): PHP/Yarn/Vite commands, phpcs, and where tests live.
- [Earth conventions](conventions.md): PHP style, module layout, migrations, and asset source rules.
- [Packages developed from this host app](vendor-packages.md): how to tell whether you are working on Earth, Core, Unicorn, or Luna, and each one's guidance and tests.
- [App environment and secrets](app-environment.md): injecting `AppEnvironmentInterface`/`SecretKeeperInterface` instead of the app.
- [templates](templates/README.md): starting points for adding documentation to this guidance tree.
- [AssetService and Vite](asset-service.md): runtime asset URL resolution, rendering, and behavior with or without Vite.
- [PHP to browser script pipeline](php-js-script-pipeline.md): how Fields and Script classes register JS in PHP and reach the browser through the import map, `main.ts`, and `renderJS()`.
- [Windwalker DI and injection](di-injection.md): how the container resolves services, constructor injection, and `#[Inject]` property injection.
