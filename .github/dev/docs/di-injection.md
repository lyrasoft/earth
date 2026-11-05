# Windwalker DI and injection

This reference explains how Windwalker's Dependency Injection (DI) container works in Earth and how to inject services into classes.

Official documentation:

- [DI overview](https://windwalker.io/documentation/components/di/)
- [Basic usage](https://windwalker.io/documentation/components/di/basic-usage.html)
- [Attributes](https://windwalker.io/documentation/components/di/attributes.html)
- [Parameters](https://windwalker.io/documentation/components/di/parameters.html)
- [Multi-level containers](https://windwalker.io/documentation/components/di/multi-levels.html)

## How it works

- Services are resolved from a container by class name or alias, for example `PathResolver::class` or `'@temp'`.
- The container builds an object by reading its constructor parameter types and resolving each one.
- Services registered as shared (for example `PathResolver` in `AppProvider.php`) return the same instance on each lookup.
- Use the container's `get()` method, or `service()` on a service-aware object such as `ConsoleApplication` or `AppContext`:

```php
$path = $this->container->get(PathResolver::class)->resolve('@temp/cache');
$path = $this->app->service(PathResolver::class)->resolve('@migrations');
```

## Constructor injection

Type-hint dependencies in the constructor. The container resolves them automatically:

```php
class FileUploadService
{
    public function __construct(
        protected Container $container,
    ) {
    }
}
```

Only classes built by the container get injected constructor arguments. Classes created with `new` do not, so pass dependencies in explicitly or fetch them from the container.

## Property injection with `#[Inject]`

If a class has no constructor injection points, or you need a dependency that is not in the constructor, mark a property with `#[Inject]`:

```php
use Windwalker\DI\Attributes\Inject;

abstract class AbstractModule implements ModuleInterface
{
    #[Inject]
    protected AppContext $app;
}
```

The attribute is defined in `vendor/windwalker/core/vendor/windwalker/framework/packages/di/src/Attributes/Inject.php` and accepts:

- `?string $id`: the service ID or alias to resolve, instead of the type hint.
- `bool $forceNew`: create a new instance instead of reusing a shared one.
- `UnitEnum|Closure|string|null $tag`: resolve a tagged service.

`#[Inject]` also works on constructor parameters (`TARGET_PARAMETER`).

## Injecting config values with `#[Ref]`

To read a single config value without injecting the whole application, use `#[Ref]` on a property. It resolves a path from the container parameters (the config tree).

Use `#[Ref('app.debug')]` to replace `$app->isDebug()`:

```php
use Windwalker\Core\Attributes\Ref;

class TinymceEditorField
{
    #[Ref('app.debug')]
    protected bool $debug;
}
```

Then use `$this->debug` where `$this->app->isDebug()` was used. `Ref` resolves the same value that `ApplicationTrait::isDebug()` returns (`config('app.debug')`). Core already reads it this way, for example `WhoopsProvider.php` and `RequestProvider.php` use `getParam('app.debug')`.

Prefer `#[Ref]` when a class only needs config values. Inject `ApplicationInterface` only when you need app methods.

## Notes

- Inject only into objects the container creates. An object made with `new` has no injection performed.
- Aliases such as `@temp` and `@logs` are defined in `etc/define.php` and `etc/runtime.config.php`. Resolve them through `PathResolver`.
- If a class cannot reach the container at all, do not invent a global. Ask how to pass the dependency in.
