# Unicorn Scripts and unicorn-next

## Boundary

- PHP `*Script` classes live in `vendor/windwalker/unicorn/src/Script/`. They only **register assets and emit small inline JS** through Core's `AssetService` (see the [AssetService reference](../docs/asset-service.md)). They do not compile anything.
- The frontend runtime is the npm package `@windwalker-io/unicorn-next` (source `vendor/windwalker/unicorn/assets/src/`, built to `assets/dist/`). Host TS imports it as `@windwalker-io/unicorn-next` (`resources/assets/src/{front,admin}/main.ts` call `useUnicorn()` and `useUnicornPhpAdapter()`); in the browser import map it is exposed as `@windwalker-io/unicorn`. Build flow is in [Vite and Fusion assets](fusion-assets.instructions.md).
- Earth uses **next mode** only (`unicorn.modules.next`). Legacy branches (SystemJS, jQuery, `@unicorn/field/*.js`, `bootstrap/*.min.css`) are not maintained or tested; do not extend them.
- Tests for these classes belong to the package: `vendor/windwalker/unicorn/tests/Script/` (run `php ..\..\bin\phpunit tests\Script` from the package directory).

## How Script classes work

- Base class `AbstractScript` (Core). Scripts are DI services; inject them and call methods in a view/controller. `$this->js()`/`$this->css()` proxy to `AssetService`.
- Every public registration method is guarded by `available()`, which de-duplicates by class, method and arguments, so calling a method repeatedly is safe and registers once.
- Methods return `static` for chaining. Inline JS is emitted through `UnicornScript::importMainThen()` / `importThen()` / `addInitialise()`, so it runs after `unicorn.js` is loaded.
- `data($name, $value)` passes PHP data to JS under dotted names (e.g. `unicorn.uri`); read it in JS via unicorn's data helpers. Arrays are merged by default.

## Main classes

| Class | Use for |
| --- | --- |
| `UnicornScript` | Core entry. `init($mainJS)` registers import map entries (`@main`, `@windwalker-io/unicorn`, `@unicorn/`) and calls `main()`; `main()` exposes URIs, asset path and base language strings. Also `translate()`, `addRoute()`, `data()`, `importScript()`, `importThen()`, `importMainThen()`, `mark()`, `exposeCsrfToken()`, `exposeCspNonce()`. |
| `FormScript` | Form fields: `flatpickr()`, `switcher()` (CSS), `cascadeSelect()`, `repeatable()`, `fileDrag()`, `multiUploader()`, `singleImageDrag()`, `modalField()`, `listDependent()`, `colorPicker()`, `showOn($field)`, `vueComponentField()`. `choices()` is not supported in next mode and throws. |
| `BootstrapScript` | Bootstrap 5 behaviors: `popover()`, `tooltip()`, `collapse()`, `offcanvas()`, `scrollspy()`, `tab()`, `toast()`, `keepTab()`, `buttonRadio()`, `iframeModal()`, `multiLevelMenu()` (CSS). |
| `EditorScript` | `tinymce($selector, $options)`. |
| `VueScript` | `vue()`, `animate()` (`@vue-animate` CSS), plus draggable support. Vue 3 is the default. |
| `AwsScript` | S3 browser upload: `s3BrowserUploader()` builds presigned upload data for `u.data('@s3.uploader.*')`. Requires an S3 storage profile. |
| `JQueryScript`, `ModernScript` | Legacy helpers; avoid in new code. |

Typical usage in a view:

`init()` is already called once per stage in `src/Module/Front/FrontMiddleware.php` (`init('@vite/src/front/main.ts')`) and `src/Module/Admin/AdminMiddleware.php` (`init('@vite/src/admin/main.ts')`); do not call it again in views. Then in a view/controller:

```php
$formScript->flatpickr();
$bootstrapScript->tooltip();
```

Check the method signature before use; do not guess option names.

## Asset paths

- `@unicorn/...` resolves to `vendor/@windwalker-io/unicorn-next/dist/...`, so the file must exist in `assets/dist/` (e.g. `switcher.css`, `multi-level-menu.css`, `editor.css`, `unicorn.js`). Verify new paths with `NextAssetPathTest`.
- `vendor/<npm-package>/...` maps to `www/assets/vendor/`, populated by the Fusion `install` task from declared NPM/Composer assets (see [fusion-assets](fusion-assets.instructions.md)). Aliases such as `@alpinejs`, `@vue-animate`, `@tinymce` come from `UnicornPackage` import map.

## What unicorn-next provides

A framework-agnostic, ES-module frontend library used by Windwalker admin/front pages:

- **App core** (`createUnicorn()`, `useUnicorn()`, `pushUnicornToGlobal()` → `window.u`): plugin system (`app.use`), `macro()`, dependency injection (`inject`/`useInject`), events, and a data store backing PHP `data()`.
- **Services** (`service/`): HTTP client, router, language/translation, URI, DOM helpers and watcher, UI (alerts, modals), animation, crypto, dynamic loader.
- **Composables** (`composable/`): `useForm`, `useGrid`, `useValidation`, `useHttp`, `useShowOn`, `useQueue`/`useStack`, Bootstrap 5 UI/modal alert, and the field features matched by `FormScript` (flatpickr, cascade select, repeatable, modal select/tree, multi uploader, file/image drag, list dependent, S3 uploader, TinyMCE, TomSelect).
- **Modules** (`module/`): the implementations behind those composables, plus `form`, `grid`, `validation`, `iframe-modal`, `http-client`, S3 multipart uploader.
- **Others**: Vue components (modal tree), polyfills, and `legacy/` (`useLegacy()`) that restores old `u.*` global methods for pre-next code.

When a PHP Script emits code like `u.$ui.iframeModal()` or `u.data(...)`, it targets this runtime, so changes to a Script method and its JS counterpart must be kept in sync.
