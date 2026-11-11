# Unicorn JS-to-PHP Adapter

How to wrap a third-party JS library so PHP can drive it through `u` (unicorn-next). Part 1 is the general workflow; Part 2 lists the cases. To support a new kind of adapter, add a new section to [Cases](#cases) and reuse the general steps.

Related: [Unicorn JS authoring style](unicorn-js-style.instructions.md) (write the JS feature first; this file covers only the PHP bridge), [Unicorn Scripts and unicorn-next](unicorn-scripts.instructions.md) (build flow), [PHP to browser script pipeline](../docs/php-js-script-pipeline.md), [E2E browser test](e2e-browser-test.instructions.md).

## Architecture

```
PHP (Field / Script / Blade)  ──emits inline JS──▶  u.$ui.<name>.init(selector, options)
                                                           │ registered in
                                              plugin/php-adapter.ts  (useUnicornPhpAdapter)
                                                           │ calls
                                              composable/use<Name>.ts  (lazy, thin)
                                                           │ dynamic import
                                              module/<name>.ts  (real implementation)
                                                           │ imports
                                                    third-party package (external)
```

Layers (all under `vendor/windwalker/unicorn/assets/src/`):

| Layer | File | Responsibility |
| --- | --- | --- |
| Module | `module/<name>.ts` | Mount/destroy the library, instance registry, DOM sync, events. Real logic lives here. |
| Composable | `composable/use<Name>.ts` | Lazy `import('../module/<name>')`. With a selector returns the instance (`module.get`), without returns the module. Exported from `composable/index.ts`. |
| Registration | `plugin/php-adapter.ts` | Adds `<name>: { init: use<Name> }` to `$ui` so PHP can call `u.$ui.<name>.init(...)`. |
| PHP side | Field / Script class | Builds the options array and emits the `init` call. |

Write the composable in modern style (async, no legacy fallback). Do not copy `useTinymce`'s legacy structure.

## General workflow

1. **Read the library's real API first.** Use the installed package source (`node_modules/<pkg>`) or its docs. Never guess option names; map PHP options to the library's actual props/options and keep adapter-only options (format, stack name, upload URL) separate from library options (e.g. a `props` sub-array passed through verbatim).
2. **Install the package at the root** (`yarn add` in `d:\www\earth`), including peer dependencies at the versions the package expects. Do not use latest majors blindly: version mismatches break the build. Check `yarn.lock` after.
3. **Mark the package external** in `vendor/windwalker/unicorn/assets/vite.config.ts` (`external: [/^@scope\/.*/]`) so the host app's Vite resolves and bundles it, not the unicorn dist.
4. **Write the module** (see [Module contract](#module-contract)).
5. **Write the composable**, export it in `composable/index.ts`, register it in `plugin/php-adapter.ts`.
6. **Write the PHP side** (see [Cases](#cases)). Check `php -l`.
7. **Build in order:** unicorn (`cd vendor/windwalker/unicorn/assets; yarn vite build`) then host (`yarn build` at the repo root). Details and workspace requirements: [unicorn-scripts](unicorn-scripts.instructions.md). Grep `www/assets` for the new chunk to confirm.
8. **Verify in a real browser** with the e2e skill. A passing build is not proof. Check: instance mounted, no console errors, value syncs, and the host page's other widgets still work.

## Module contract

- **Instance registry** keyed by selector (`get`, `create`, `destroy`, `getInstances`). `get` returns the existing instance or creates one; destroying removes it.
- **Dynamic imports** for the library and its CSS inside `mount()`; never import at module top level, so pages that don't use it pay nothing.
- **Controller class** exposing a small stable API: `getValue`, `setValue`, `insert`, `setEditable`, `destroy`, plus whatever the library needs.
- **Keep the original form element as the source of truth.** Hide it, mount the widget next to it, and write changes back, dispatching `input` and `change` events so validation and unicorn form code see them. Dispatch a `<name>:create` style event when ready.
- **Uploads / HTTP:** use `useHttpClient`; report progress through events and `useStack(stack_name)` push/pop; read the result URL from the standard response shape.
- **Types:** use an `interface` for the module shape. A `typeof import('./self')` type breaks the dts build (API Extractor).
- **Cleanup:** `destroy()` unmounts the app, removes created DOM, restores the original element and removes the registry entry.

## Pitfalls (learned)

- **Global CSS leaks.** Library CSS can collide with Bootstrap or admin styles (e.g. a global `.dropdown-menu` forces `display:flex` and breaks Bootstrap dropdowns). Import the CSS with `?inline`, scope or rewrite offending selectors in code, and inject one `<style id="...">` once. Verify no built CSS file still contains the unscoped rule.
- **Layout assumptions.** Library roots may be `display:flex` row (toolbar beside content). Wrap the mount in your own container (`c-<name>-editor`) and add a one-time injected style to fix direction/width. Inspect the live DOM with computed styles rather than guessing.
- **`?inline` / CSS imports need `// @ts-ignore`** and are resolved by the host Vite, which is why the package must be external.
- **Build output is committed separately.** `vendor/windwalker/unicorn` is its own git repo with a tracked `dist/`; `www/assets/` is build output. Rebuild both after any module change.
- **Do not run `yarn install` again** to fix a one-package problem; use `yarn add` with pinned versions.
- **Prefer `$u`-registered adapters over inline `<script>` in views;** let the PHP class emit the init call through `UnicornScript::importMainThen()`.

## Checklist

- [ ] Options mapped to the library's real API; adapter-only options separated
- [ ] Package + peers installed, package marked external
- [ ] Module, composable, `index.ts` export, `$ui` registration
- [ ] PHP side emits `u.$ui.<name>.init(...)`
- [ ] CSS scoped; layout checked in the live DOM
- [ ] unicorn dist and host assets rebuilt
- [ ] E2E: mounted, value synced, no console errors, nothing else broken

## Cases

Each case: what PHP side to write, how to call, and case-specific notes. Add new case types below with the same headings.

### Case: Form field (editor widget)

Example: `YiitapEditorField` + `u.$ui.yiitap` (Yiitap block editor), modeled on unicorn's `TinymceEditorField`.

- **PHP:** `src/Field/YiitapEditorField.php`, extends unicorn's `AbstractEditorField`. It builds the options array and emits `u.$ui.yiitap.init(selector, options)`. Reuse `AbstractEditorField` helpers (selector, upload URL via `getBuiltInUploadUrl`) instead of reimplementing.
- **Options shape:** `props` (passed straight to the library component), plus adapter-only `format` (`html`|`json`), `unicorn.stack_name`, `imageUploadUrl`. Add `get<Lib>Locale()` when the library's locale codes differ from the app's (e.g. `zh-TW` → `zh-Hant`).
- **Swap into a form:** replace the field class and `use` import in the form (e.g. Luna `Article/Form/EditForm.php`: `introtext`, `fulltext`), and drop options the new library doesn't support (e.g. TinyMCE `height`). Other forms keep their old field until migrated deliberately.
- **Verify (e2e):** open the edit page, wait for `.c-<name>-editor`, type content, confirm the hidden textarea value updated and no console errors; also check the dropdown/popup UI and that Bootstrap dropdowns still work.
- **Extra pitfalls:** hidden textarea must still submit; `required` validation then needs the synced value; check both `format` outputs.

<!-- Add further cases here, e.g. "Case: Script (non-field behavior)", "Case: Vue component field", "Case: Uploader". Use the same bullets: PHP, options shape, wiring, verify, pitfalls. -->
