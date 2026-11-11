# Unicorn JS Authoring Style

Quick reference for writing new frontend features in `vendor/windwalker/unicorn/assets/src/`. PHP is **optional**: write the JS feature first; only add a PHP adapter when PHP must call it (see [JS-to-PHP adapter](unicorn-js-php-adapter.instructions.md)). Build flow: [unicorn-scripts](unicorn-scripts.instructions.md).

## Layout

| Dir | Role |
| --- | --- |
| `module/<name>.ts` | Real implementation. Heavy code and third-party imports live here. |
| `composable/use<Name>.ts` | Public entry. Thin, lazy, fully typed. Exported from `composable/index.ts`. |
| `service/`, `plugin/`, `app.ts` | Core runtime. Don't put feature logic here. |
| `plugin/php-adapter.ts` | Only for features PHP calls (`$ui`). Skip otherwise. |

## Entry rule: `useXxx()` + dynamic `import()` + real types

The entry is always `useXxx`. It loads the module with `import()` so the code is split into a chunk and only fetched on use. **The return type must come from the module**, never `any`.

Choose the pattern by what the feature exposes:

### 1. Return the whole module

```ts
// composable/useFieldFlatpickr.ts
export function useFieldFlatpickr(): Promise<typeof import('../module/field-flatpickr')> {
  return import('../module/field-flatpickr');
}
```

Avoid `Promise<any>`; `typeof import('...')` keeps the module's types without a static import (no eager load).

### 2. Module + optional instance (selector → controller)

Use overloads and `import type` for the types (erased at build, so still lazy):

```ts
import type { FooController, FooModule, FooOptions } from '../module/foo';

export async function useFoo(): Promise<FooModule>;
export async function useFoo(selector: string, options?: FooOptions): Promise<FooController>;
export async function useFoo(selector?: string, options: FooOptions = {}): Promise<any> {
  const module = await import('../module/foo');

  return selector ? module.get(selector, options) : module;
}
```

- Types (`FooController`, `FooOptions`) must be `export`ed from the module and imported with **`import type`** in the composable. A value `import` defeats lazy loading.
- Define `FooModule` as an `interface` listing the module's exports. Do **not** write `export type FooModule = typeof import('./foo')` inside `foo.ts` itself: it breaks the dts build (API Extractor).
- Implementation signature may return `Promise<any>`; the public overloads carry the types.

### 3. Single function (feature = one call)

```ts
export async function useBar(el: HTMLElement, options?: BarOptions): Promise<BarController> {
  const { createBar } = await import('../module/bar');

  return createBar(el, options);
}
```

Destructure the import so only what is needed is referenced.

### 4. Cheap, dependency-free state

If there are no heavy deps (e.g. `useQueue`, `useStack`), skip `import()` and implement directly in the composable.

## Module rules

- **Named exports**, no default export (consumers use `module.get`, destructuring). 
- **Import third-party libs and CSS inside functions** (`await import('pkg')`), never at the module's top level, when the module itself may be loaded on pages that don't need them.
- **Instance registry** for per-element features: `Record<string, Controller>` keyed by selector, with `get`, `create`, `destroy`, `getInstances`.
- Expose a **controller class** with a small, stable API; keep internals private.
- **Don't touch globals at import time** (no DOM access, no `window` assignments); do it inside functions so SSR/tests and tree-shaking are safe.
- Use existing services: `useHttpClient`, `useStack`, `useQueue`, `useUnicorn()`; don't hand-roll fetch or toasts.
- Dispatch DOM events (`<name>:create`, `upload-start`, …) for outward communication; sync form elements by setting `value` and firing `input`/`change`.
- Types: `interface` for public shapes, `import type` for type-only imports, no `any` in public signatures.

## Third-party dependencies

- Add the package at the **host root** (`yarn add`, pin versions matching peers); do not run a full `yarn install`.
- Mark it `external` in `vendor/windwalker/unicorn/assets/vite.config.ts` so the host app bundles it. Otherwise it is inlined into the unicorn dist.
- Library CSS: import via `?inline` and scope/inject it yourself when it has global selectors that can collide with Bootstrap.

## Exposing to the app

- **JS-only use:** export from `composable/index.ts` so apps can `import { useFoo } from '@windwalker-io/unicorn-next'`.
- **PHP use:** additionally register in `plugin/php-adapter.ts` `methods` (e.g. `foo: { init: useFoo }`), then write the PHP emitter. Keep this step separate and only when needed.

## Verify

1. `cd vendor/windwalker/unicorn/assets; yarn vite build` (dts errors show here), then `yarn build` at the repo root.
2. Grep `dist/` and `www/assets/` for the new chunk; confirm the chunk is lazy (not in the main `unicorn.js`).
3. Check types resolve in an editor: `const m = await useFoo()` should show `FooModule`, not `any`.
4. Browser check via the e2e skill for real behavior.

## Checklist

- [ ] Entry named `useXxx`, in `composable/`, exported from `index.ts`
- [ ] `import()` inside the function; types via `import type` / `typeof import()`; no `Promise<any>` in public signature
- [ ] Heavy deps and CSS loaded lazily inside the module
- [ ] Types exported from the module; `interface` (not self `typeof import`) for the module shape
- [ ] PHP adapter added only if PHP must call it
- [ ] Built, chunk confirmed lazy, behavior checked in browser
