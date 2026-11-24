# App environment and secrets injection

Do not inject the whole application only to read the debug flag or app secret. Inject:

- `Windwalker\Core\Application\AppEnvironmentInterface`: `isDebug()`, `getMode()`, `getName()`, `getVerbosity()`, `isVerbose()`, `isCli()`.
- `SecretKeeperInterface`: `getSecret(?string $deriveFor = null)`.

Similarly, prefer `PathResolver`, `Navigator`, `AppRequest`, `SystemUri`, and `MaintenanceManager` over the matching `$app->...()` helpers. For config-only values, see `#[Ref]` in [Windwalker DI and injection](di-injection.md).

## Overriding and testing

- `AppProvider` registers the config-based `AppEnvironment` and `SecretKeeper` through `AppEnvironmentProvider`, which skips an interface that is already registered. To override them with an implementation that does not read config, register your own for the interface before the app boots.
- In unit tests, build `new AppEnvironment(new Config([...]))` directly.
- A test app factory that does not boot (for example `tests/Core/Application/Stub/TestWebAppFactory.php` in Core) must register `AppEnvironmentProvider` itself once a class injects these interfaces.
