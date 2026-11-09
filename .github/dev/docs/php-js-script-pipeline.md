# PHP to browser script pipeline (Unicorn)

## Purpose and boundary

How a PHP-side registration (a Field, a `*Script` class) turns into JavaScript running in the browser. Read this before building a test environment, debugging "the component does nothing", or reproducing script behavior outside a normal page. Asset URL resolution and Vite are in [AssetService and Vite](asset-service.md); the Script classes and building package JS are in [Unicorn Scripts](../instructions/unicorn-scripts.instructions.md). This page only connects them.

## The chain

1. **Stage setup (every request).** [`FrontMiddleware`](../../../src/Module/Front/FrontMiddleware.php) / [`AdminMiddleware`](../../../src/Module/Admin/AdminMiddleware.php) call `UnicornScript::init('@vite/src/{front|admin}/main.ts')` in `preprocess()`.
   - `init()` registers import-map entries: `@main` (the host entry), `@windwalker-io/unicorn`, `@windwalker-io/unicorn/`, `@unicorn/` (all under `vendor/@windwalker-io/unicorn-next/dist/`), and adds the entry's Vite CSS.
   - `main()` registers base translations, `unicorn.uri` (system URIs and asset path/root) and the CSRF token as data.
2. **Host entry (JS).** `resources/assets/src/{front,admin}/main.ts` creates the app: `useUnicorn()`, `useUIBootstrap5()`, `useUnicornPhpAdapter()`, `useLuna()`, `pushUnicornToGlobal()`, and exports `{ app, u }`. This is what `@main` points to.
3. **Adapter.** `useUnicornPhpAdapter()` installs `UnicornPhpAdapter` (`vendor/windwalker/unicorn/assets/src/plugin/php-adapter.ts`), which sets `u.$ui.*` (e.g. `fileDrag` -> `useFieldFileDrag`, `flatpickr`, `modalField`, `repeatable`). PHP Scripts emit calls to these names.
4. **Registration during rendering.** Something rendered on the page asks a Script to register what it needs. For a Field, its **layout** does it: `views/ui/bootstrap5/field/file-drag.blade.php` calls `FormScript::fileDrag()`, which translates `unicorn.field.file.drag.*` and calls `UnicornScript::importMainThen('u.$ui.fileDrag()')`. That only appends the code to `UnicornScript::$initialise` (de-duplicated by `available()`). Nothing is output yet.
5. **Output at the end of the page.** The root layout [`views/global/html.blade.php`](../../../views/global/html.blade.php) (front and admin layouts both extend it) outputs, after the body content: `getTeleport()->render()`, `getImportMap()->render()`, then `renderJS(true)`. `renderJS()` fires `AssetBeforeRender`, and `UnicornAssetSubscriber::handleJS()` (in `vendor/windwalker/unicorn/src/Listener/`) then:
   - prepends `document.__unicorn = {...}` from `UnicornScript::getData()`;
   - joins `$initialise` into one block, prefixed with `const { u } = module;`;
   - registers it through `importThen('@main', ...)`, i.e. a module script that imports `@main` and runs the block.
6. **Browser.** The import map resolves `@main` to the built `main.ts`; the module script gets `u`, so `u.$ui.fileDrag()` runs and upgrades `<uni-file-drag>` elements.

## What this means in practice

- **Using the object the normal way sets up its own scripts.** Because step 4 happens in the layout, rendering a Field (not writing its output HTML by hand) registers its init code automatically.
- **Order matters only at the end.** Registration can happen anywhere during rendering, but must be before `renderJS()` in `html.blade.php` runs (end of body). Code that registers after that point is not output.
- **The page must extend the root layout** (or otherwise output the import map and `renderJS()`). A page without it has no `@main`, no data and no init code.
- **Hand-written markup skips step 4**, so the init call never exists and the custom element never upgrades. Also missing: translations, `unicorn.uri` data and CSRF are only registered by what the real code path calls.

## Reproducing the environment when you must rewrite

Only when using the object itself is not possible (state why in the test notes):

1. Render inside a page that extends the root layout, in the right stage (front or admin), so steps 1, 2, 5 and 6 exist.
2. Find what the target registers: grep the target and its layout for `FormScript`, `UnicornScript`, `$asset`; read the called method to see the `importMainThen()` code, translations, and data.
3. Register the same things in the scratch view/controller by calling those Script methods, not by pasting JS.
4. Confirm the runtime is active before testing behavior, in the browser: `document.__unicorn` exists, `u.$ui.<name>` is a function, and the custom element is defined (`customElements.get('uni-file-drag')`) and upgraded.

## Diagnosing "the component does nothing"

| Check | Meaning if it fails |
| --- | --- |
| `document.__unicorn` is missing | `renderJS()` not reached; the page does not use the root layout or JS was registered too late. |
| No `importmap` with `@main` | `UnicornScript::init()` was not called for this stage (middleware not applied). |
| `u.$ui.<name>` is not a function | The adapter or the built `unicorn-next` dist is stale or missing the feature; rebuild (see [Unicorn Scripts](../instructions/unicorn-scripts.instructions.md)). |
| `u.$ui.<name>()` never ran | Nothing called the Script method (hand-written markup, or the layout was skipped). |
| Element not upgraded after the call | The element's module failed to load; check the console and that the changed chunk exists in `www/assets/`. |
