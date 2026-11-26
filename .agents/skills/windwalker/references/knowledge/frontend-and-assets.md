# Vite and Fusion Assets

## Fusion build

- The host's `vite.config.mts` loads `fusionfile.ts` with `useFusion(() => import('./fusionfile'))`. Build entries, tasks, aliases, and output paths are configured in `fusionfile.ts`.
- Output directory: `www/assets/` (`fusion.outDir()`); manifest: `www/assets/manifest.json`.
- Aliases:
  - `~` → `resources/assets`
  - `~js` → `resources/assets/src`
  - `~vendor` → `www/assets/vendor`
  - `vue` → `vue/dist/vue.esm-bundler.js`
- Tasks and outputs:
  - `js()`: `resources/assets/src/front/main.ts` → `js/front/main.js`; `resources/assets/src/admin/main.ts` → `js/admin/main.js`
  - `css()`: `scss/front/bootstrap.scss` → `css/front/bootstrap.css`; `scss/front/main.scss` → `css/front/main.css`; `scss/admin/main.scss` → `css/admin/main.css`
  - `images()`: `resources/assets/images/**/*` → `images/`
  - `install()`: uses `installVendors()` to install `@fortawesome/fontawesome-free` in `www/assets/vendor/`.
- The `assets/` directories in modules and scripts / styles embedded in Blade are included in the corresponding stage by `jsModulizeDeep()` and `cssModulizeDeep()`.
- Commands: `yarn dev` (Vite dev server; does not create a static build), `yarn build`, `yarn build:prod`, and `yarn watch`.

References:

- [Setup and Build | Getting Started](https://windwalker.io/documentation/framework/frontend/setup.md)
- [Styles and Assets](https://windwalker.io/documentation/framework/frontend/styles-and-assets.md)
- [Frontend Integration | Getting Started](https://windwalker.io/documentation/framework/frontend/overview.md)

## Asset paths and outputs

- Edit source files in `resources/assets/` or module `assets/`; do not edit generated files in `www/assets/`.
- Paths beginning with `~/` are Vite aliases and must be configured in both `fusion.alias()` and `tsconfig.json`'s `paths`.
- Output paths for fixed entries are stable; imported chunks may have hashed names and live in `www/assets/chunks/`. Referenced assets such as images and fonts may be output to `images/` and `fonts/`.
- To trace a source to its actual output, check `www/assets/manifest.json`. Do not guess hashed names.
- When cleaning build output, do not clear all of `www/assets/`; preserve non-build content such as `upload/` and `vendor/`.

Reference:

- [Asset Management | Views](https://windwalker.io/documentation/framework/core-concepts/views/asset-management.md)

## Runtime resolution (AssetService)

- `AssetService` registers and renders CSS / JS links and resolves URLs; it does not compile source files.
- Use `$asset->path('~/images/logo.png')` to get an asset URL or `$asset->root('~/images/logo.png')` to get an absolute URL.
- `$asset` is automatically available in general Layouts or Blade templates; inject `AssetService` manually in a service.
- `ViteResolver` uses two signals:
  - Dev server file (default `tmp/vite-server`): when present, resolves to the server URL and adds `@vite/client` to JS.
  - Vite manifest (default `@public/assets/manifest.json`; on this host, `www/assets/manifest.json`): maps source entries to the output `file` and CSS.
- `~/` is normalized to `@vite/`, with `resources/assets` as the default base.
- `viteEntry()` registers a JS entry and includes that entry's CSS from the manifest.
- If neither a Vite server nor a manifest is available, Vite aliases are not resolved, and the URI is handled by the regular asset system.
- Regular local URLs (such as `css/site.css`) are unaffected by Vite and continue to use normal path resolution.
- Local file lookup: debug mode prefers unminified files; production mode prefers `.min` files. If the preferred version is missing, it falls back to the other.
- `has()` checks whether a local asset exists and returns the resolved URL or `false`. File existence is not checked during rendering.

Reference:

- [Asset Management | Views](https://windwalker.io/documentation/framework/core-concepts/views/asset-management.md)

## Configuration and troubleshooting

- The configuration namespace is `asset`:
  - `asset.folder`: defaults to `assets`
  - `asset.uri`: overrides the system URI path
  - `asset.vite.base`: defaults to `resources/assets`
  - `asset.vite.manifest`: defaults to `@public/assets/manifest.json`
  - `asset.vite.server_file`: defaults to `tmp/vite-server`
- When an asset is missing or outdated:
  1. Check whether the URI is a regular URL or a Vite alias (`@vite/...`, `~/...`).
  2. For a Vite alias, check the dev server file; otherwise check the source key and output `file` in the manifest.
  3. For a regular URL, check the public asset directory and both regular and `.min` files, and confirm version control settings.
  4. If the HTML still has no correct URL, check `AssetBeforeRender` event listeners and whether the CSS was registered as footer content.

References:

- [Asset Management | Views](https://windwalker.io/documentation/framework/core-concepts/views/asset-management.md)
- [Package JavaScript | Package Development](https://windwalker.io/documentation/framework/packages/package-javascript.md)
