# Unicorn Scripts and unicorn-next

## Boundary

- PHP `*Script` classes live in `vendor/windwalker/unicorn/src/Script/`. They only **register assets and emit small inline JS** through Core's `AssetService` (see the [AssetService reference](../docs/asset-service.md)). They do not compile anything.
- The frontend runtime is the npm package `@windwalker-io/unicorn-next` (source `vendor/windwalker/unicorn/assets/src/`, built to `assets/dist/`). Host TS imports it as `@windwalker-io/unicorn-next` (`resources/assets/src/{front,admin}/main.ts` call `useUnicorn()` and `useUnicornPhpAdapter()`); in the browser import map it is exposed as `@windwalker-io/unicorn`. Build flow is in [Vite and Fusion assets](fusion-assets.instructions.md).
- Earth uses **next mode** only (`unicorn.modules.next`). Legacy branches (SystemJS, jQuery, `@unicorn/field/*.js`, `bootstrap/*.min.css`) are not maintained or tested; do not extend them.
- Tests for these classes belong to the package: `vendor/windwalker/unicorn/tests/Script/` (run `php ..\..\bin\phpunit tests\Script` from the package directory).

## Building and testing package JS (unicorn-next / core assets)

Edit TS under `vendor/windwalker/unicorn/assets/src/` (or `vendor/windwalker/core/assets/*`), then rebuild so `assets/dist/` and the host's `www/assets/` reflect it. Check the setup first:

1. **Workspaces enabled?** Root `package.json` has a top-level `"workspaces"` array (`vendor/windwalker/unicorn/*`, `vendor/windwalker/core/assets/*`). If it is only under the `"//"` key, it is disabled. Confirm the link: `(Get-Item node_modules\@windwalker-io\unicorn-next).LinkType` is `Junction` (same for `@windwalker-io\core`, `@windwalker-io\fusion-next`).
2. **If enabled**, the package `node_modules` are linked to the host's, so run directly inside the package (no separate install):
   - `cd vendor\windwalker\unicorn\assets` → `yarn dev` (watch) or `yarn build` (one-off, writes `dist/`). Use `yarn build:assets` / `yarn dev:assets` for the `vite.assets.config.ts` build. Same for `vendor\windwalker\core\assets\<pkg>`.
3. **If not enabled**, the host `node_modules` lacks the package's devDependencies (verified: `@vitejs/plugin-vue`, `rimraf`, `unplugin-dts`, `rollup-plugin-tree-shakeable`, `sprintf-js`, `punycode`, etc. are missing), so `..\..\..\..\node_modules\.bin\vite build` fails with `UNRESOLVED_IMPORT` / `ERR_MODULE_NOT_FOUND`. Do not edit `package.json` or install into vendor on your own. Stop and ask the user to enable `workspaces` in the root `package.json` (`"workspaces": ["vendor/windwalker/unicorn/*", "vendor/windwalker/core/assets/*"]`) and run `yarn install` at the repo root, then retry step 2. Only if the missing packages exist elsewhere (check with `Test-Path node_modules\<name>`) is the host-`node_modules` build worth trying.
4. **Push the build to the host:** the host serves a copy under `www/assets/vendor/@windwalker-io/unicorn-next/` (a Junction to `node_modules\@windwalker-io\unicorn-next`, so it only reflects your package build when step 2's workspace link is in place; after adding `workspaces` the user must run `yarn install` once, check that `node_modules\@windwalker-io\unicorn-next` has `LinkType` `Junction`) plus chunks like `www/assets/chunks/`. Run `yarn build` from the repo root to rebuild host chunks. In PowerShell `yarn build -- install` does not reach Fusion (Vite treats `install` as the root dir and fails with `UNRESOLVED_ENTRY`); run the install task as `node node_modules\vite\bin\vite.js build --mode development -- install`. Then verify the changed code appears in `www/assets/vendor/@windwalker-io/unicorn-next/dist/` and in the new `www/assets/chunks/grid-*.js`.
5. Verify the result by grepping the built `dist/` file for the changed code; a browser check is needed for real behavior. `vendor/windwalker/unicorn` is its own git repo: commit/PR changes there, and note host `www/assets/` is build output.

## How Script classes work

- Base class `AbstractScript` (Core). Scripts are DI services; inject them and call methods in a view/controller. `$this->js()`/`$this->css()` proxy to `AssetService`.
- Every public registration method is guarded by `available()`, which de-duplicates by class, method and arguments, so calling a method repeatedly is safe and registers once.
- A Blade layout calls its Script method when rendered, so rendering a php layout with js, it registers their own init code. `importMainThen()` only queues code; the root layout `views/global/html.blade.php` outputs it through `renderJS()`. Full chain, and how to rebuild or diagnose this environment: [PHP to browser script pipeline](../docs/php-js-script-pipeline.md). Prefer using the real Field/Script over hand-written markup.
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
