# Unicorn JS development in Earth

Use this reference when adding a frontend feature to `vendor/windwalker/unicorn/assets/src/`, especially when PHP fields or Script classes must initialize it. Unicorn is a separate repository; follow its own contributor instructions and tests.

## Earth integration

- Earth uses Unicorn's `next` mode (`unicorn.modules.next`). Do not extend legacy SystemJS, jQuery, or legacy asset branches for Earth features.
- `src/Module/Front/FrontMiddleware.php` and `src/Module/Admin/AdminMiddleware.php` initialize their own entries with `UnicornScript::init('@vite/src/{front|admin}/main.ts')`. Rendered fields and layouts register script calls before `views/global/html.blade.php` outputs the teleport buffers, import map, and `renderJS(true)` at the end of the page.
- Keep PHP Script changes and their matching JS runtime changes in sync.
- Changes to `vendor/windwalker/unicorn` or `vendor/windwalker/core` belong to those package repositories. Build package assets first, then rebuild Earth host assets according to the package workspace setup.

## JS-only features

- Put implementation and heavyweight dependencies in `module/<name>.ts`; expose a thin `use<Name>` composable from `composable/` and export it from `composable/index.ts`.
- Use dynamic `import()` for feature modules and heavy dependencies so unused features remain lazy. Avoid DOM or global side effects at module import time.
- Export real public types. Use `import type` for type-only dependencies, and define module shapes with interfaces rather than self-referential `typeof import('./module')` aliases.
- Use Unicorn services such as `useHttpClient`, `useStack`, and `useQueue` rather than hand-rolling shared behavior.

## PHP adapter features

- Add a PHP adapter only when PHP needs to initialize the JS feature. Register its composable in `plugin/php-adapter.ts`, then have the PHP Field or Script emit `u.$ui.<name>.init(...)` through the Unicorn Script helpers.
- Keep library options separate from adapter-specific options and verify option names against the installed library's API.
- Keep the original form control as the source of truth: synchronize its value and dispatch the expected `input` / `change` events. Clean up mounted UI, created DOM, and registry entries in `destroy()`.
- Load third-party JS and CSS lazily. Scope global CSS that could collide with Bootstrap or the admin theme.

## Dependencies and validation

- Add frontend dependencies at the Earth root with peer-compatible versions; mark packages external in the Unicorn Vite configuration when they must be bundled by the host rather than the Unicorn distribution.
- Check that root Yarn workspaces link the Unicorn package before building. Follow the [Earth asset and build guidance](assets-and-builds.md) for package and host build order; do not install or enable workspaces without user approval when dependencies are missing.
- Verify the generated chunk is lazy, types resolve correctly, and the feature works in a real browser. A successful build alone does not verify PHP/JS integration.

The legacy detailed adapter guide remains at `.github/dev/instructions/unicorn-js-php-adapter.instructions.md` for existing links; keep its case-specific examples consistent with these canonical development rules.
