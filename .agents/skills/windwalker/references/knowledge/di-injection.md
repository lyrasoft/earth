# Windwalker DI and Injection

## Configuration location

- Service bindings go in the `bindings`, `providers`, `aliases`, `extends`, `attributes`, and other sections of `etc/di.php` (the standalone DI configuration).
- In an Earth project, DI configuration is loaded by `'di' => include __DIR__ . '/di.config.php'` in `etc/config.php`, and the `di` section is processed last.
- Path aliases (`@temp`, `@logs`) are defined in `etc/define.php` and `etc/runtime.config.php`, and resolved by `PathResolver`.

## Getting services

- Get a service from the container by class name or alias with `$this->container->get(...)`, or use `$this->app->service(...)`.
- Examples:

```php
$path = $this->container->get(PathResolver::class)->resolve('@temp/cache');
$path = $this->app->service(PathResolver::class)->resolve('@migrations');
```

- `AppProvider` registers `PathResolver::class` as shared with `prepareSharedObject(PathResolver::class)`, so each retrieval returns the same instance.

## Injection methods

- **Constructor injection**: add a type hint to the constructor parameter; the container resolves it automatically.
- **Property injection `#[Inject]`**: can be used on properties and parameters. Defined in `vendor/windwalker/framework/packages/di/src/Attributes/Inject.php`.
  - `id`: service ID or alias.
  - `forceNew`: create a new instance.
  - `tag`: a string or enum value used to resolve a tagged service.
- Resolution order: first the ID or first class/interface type, then a registered service for that tag, and finally instantiate a concrete class; interfaces are not instantiated automatically.
- **`#[Autowire]`**: creates unresolved dependencies; can be used on classes, callables, and parameters.
- **`#[NoAutowire]`**: skips automatic injection for a single parameter.
- **`#[Service]`**: resolves a shared service; can be used on properties, parameters, and classes.
  - `shared: false`: not shared.
  - `tag`: resolve a tagged service.
  - `providedIn`: restrict the container level where the service is available.
- Only objects created by the container are injected; objects created with `new` are not.

```php
use Windwalker\DI\Attributes\Inject;

abstract class AbstractModule implements ModuleInterface
{
    #[Inject]
    protected AppContext $app;
}
```

## Binding services by tag with a function

- Put a closure that accepts `?string $tag` in `bindings` and return different implementations based on the tag.
- The caller specifies a variant with `tag`; when no tag is supplied, use the `default` branch.

```php
// etc/di.config.php, inside the returned array
'bindings' => [
    ChatModelInterface::class => function (
        Container $container,
        ?string $tag = null,
    ) {
        return match ($tag) {
            'claude' => $container->newInstance(ChatClaudeDriver::class),
            default => $container->newInstance(ChatGPTDriver::class),
        };
    },
],
```

```php
$gpt = $app->retrieve(ChatModelInterface::class);
$claude = $app->retrieve(ChatModelInterface::class, tag: 'claude');
```

- A closure binding creates a new object for each retrieval. To share one instance per tag, use `bindShared()` in a provider instead.
- Using an enum as a tag helps prevent typos: change the parameter type to `?ChatModelDriver $tag`. To accept both strings and enums, use `string|\UnitEnum|null` and convert with the rich enum's `wrap()`.
- For untrusted input, use `tryWrap()` (returns `null` for unknown values) instead of `wrap()` (throws `ValueError` for unknown values).
- Tags are case-sensitive; `'SMS'` and `'sms'` are different tags.

## Provider lifecycle

- A provider implements `register()` and may optionally implement `bootDeferred()`, `bootBeforeRequest()`, and `releaseAfterRequest()`.
- Multiple container levels and `providedIn` relate to request / application levels.

## Note

- If a class cannot access the container, do not create a global variable; ask how the dependency should be passed in.

## References

- [Dependency Injection concepts](https://windwalker.io/documentation/framework/core-concepts/dependency-injection/dependency-injection.md)
- [Service Variants](https://windwalker.io/documentation/framework/core-concepts/dependency-injection/service-variants.md)
- [DI overview](https://windwalker.io/documentation/components/di/index.md)
- [Basic usage](https://windwalker.io/documentation/components/di/basic-usage.md)
- [Attributes](https://windwalker.io/documentation/components/di/attributes.md)
- [Parameters](https://windwalker.io/documentation/components/di/parameters.md)
- [Multiple containers](https://windwalker.io/documentation/components/di/multi-levels.md)
