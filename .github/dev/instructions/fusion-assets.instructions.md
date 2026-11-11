# Vite and Fusion assets

## Build flow

- The root `../../../vite.config.mts` loads `fusionfile.ts` through `useFusion()`. Keep host build inputs, tasks, aliases, and output paths in `fusionfile.ts`; do not duplicate them as separate Vite inputs.
- Fusion runs on top of Vite. Its default build configuration writes a manifest to `www/assets/manifest.json`, keeps the output directory, and places generated assets under `www/assets/`.
- The default Fusion tasks are `js`, `css`, and `images`. `js()` builds the Front and Admin entries to `js/front/main.js` and `js/admin/main.js`; `css()` builds the shared Bootstrap stylesheet and Front/Admin stylesheets under `css/`. `images()` copies `resources/assets/images/` into `images/`.
- To inspect compiled files, look under `www/assets/`. Files without an explicitly configured output path (such as dynamically imported modules and other generated chunks) are written under `www/assets/chunks/`; use `www/assets/manifest.json` to map build inputs to their emitted filenames.
- Core's `jsModulizeDeep()` and `cssModulizeDeep()` include module-local `assets/` files and eligible Blade-embedded scripts/styles for the matching stage. Put feature-specific assets alongside their module; shared entry sources belong under `resources/assets/`.
- The named `install` task uses Core's `installVendors()` processor to populate `www/assets/vendor/` from declared NPM and Composer package assets. It is separate from the default build tasks; the root postinstall script invokes it with `yarn build -- install`.

## Source-to-output map

| Source / purpose | Command | Typical output |
| --- | --- | --- |
| `resources/assets/src/front/main.ts`, plus Front module scripts and eligible Blade scripts | `yarn build` or `yarn build:prod` (default `js` task) | `www/assets/js/front/main.js`; dynamically imported or generated modules under `www/assets/chunks/` |
| `resources/assets/src/admin/main.ts`, plus Admin module scripts and eligible Blade scripts | `yarn build` or `yarn build:prod` (default `js` task) | `www/assets/js/admin/main.js`; dynamically imported or generated modules under `www/assets/chunks/` |
| `resources/assets/scss/front/bootstrap.scss` | `yarn build` or `yarn build:prod` (default `css` task) | `www/assets/css/front/bootstrap.css` |
| `resources/assets/scss/front/main.scss`, Front module `assets/*.scss`, and eligible Blade styles | `yarn build` or `yarn build:prod` (default `css` task) | `www/assets/css/front/main.css`; extracted/imported CSS may be under `www/assets/chunks/css/` |
| `resources/assets/scss/admin/main.scss`, Admin module `assets/*.scss`, and eligible Blade styles | `yarn build` or `yarn build:prod` (default `css` task) | `www/assets/css/admin/main.css`; extracted/imported CSS may be under `www/assets/chunks/css/` |
| `resources/assets/images/**/*` | `yarn build` or `yarn build:prod` (default `images` task) | Corresponding files under `www/assets/images/` |
| Declared NPM/Composer vendor assets | Explicit `yarn build -- install` task | `www/assets/vendor/` |

The explicit JS/CSS entry destinations above are the stable outputs. Their imports can produce hashed files under `chunks/`; assets referenced by JS/CSS may also be emitted into `images/` or `fonts/`. Use `www/assets/manifest.json` to trace a source key to the actual emitted `file` rather than guessing a hash or assuming every module gets a separate output.

When checking a missing output, first run `yarn build` (or `yarn build:prod` when verifying production output), then find the source path in `manifest.json` and confirm its emitted `file` exists. For module assets, check that the module is in the matching `Front` or `Admin` stage, its TS/SCSS file is under that module's `assets/` directory, and the shared main entry is configured. For Blade JS, the processor looks for `<script lang="ts|typescript" data-macro="...">`; Blade CSS looks for `<style>` or `<script>` with `data-macro` and `type="text/scss"` or `"text/css"`. Do not expect `yarn dev` to create a complete static build: it starts Vite's development server and serves source modules through Vite. Do not clear all of `www/assets/` when cleaning generated output: preserve `upload/`, `vendor/`, runtime/admin files, and files not emitted or referenced by the current build.

## Development and runtime

- Use the root Yarn scripts for asset work: `yarn dev` for Vite's dev server, `yarn build` for a development-mode build, `yarn build:prod` for a production build, and `yarn watch` for a watched development build. Run these Yarn commands directly rather than substituting `npm` commands. Do not run `yarn install`; dependency installation is performed by the user in their own environment.
- The ignored root `.yarnrc.yml` references `FA_TOKEN` for the Font Awesome private registry. Try the requested `yarn dev` or `yarn build` directly first. If Yarn stops before running the script because `FA_TOKEN` is unset, retry that asset command with a process-scoped non-empty placeholder; these scripts use the already-installed dependencies and do not install packages. Never persist a placeholder or use it for dependency installation.
- Core's PHP `ViteResolver` uses the Vite server file during development and `www/assets/manifest.json` for built assets. `AssetService` resolves and renders registered CSS/JS links and adds `@vite/client` while the dev server is running; it does not compile assets.
- Edit source files in `resources/assets/` or module `assets/` directories, not generated files in `www/assets/`. Keep TypeScript aliases consistent between `tsconfig.json` and the Fusion configuration.
- If `yarn build` (or `yarn build:prod`) fails because files under `www/assets/` are locked (for example `EBUSY`, `EPERM`, or "used by another process"), the user may be running `yarn watch` or `yarn dev` in their Windows terminal. Do not kill processes on your own. Check whether the expected outputs were still updated: inspect the timestamps of the entries in `www/assets/manifest.json` and their emitted `file` paths under `www/assets/`. If the chunks are current, the lock is harmless. If they are stale or missing, stop and ask the user how they want to handle the locked process (for example, stop their watch and rerun the build, or let the watch rebuild). Only stop a process by PID after the user agrees.

## Core package boundary

`vendor/windwalker/core/assets/fusion` implements the Vite Fusion plugin and task runner; `vendor/windwalker/core/assets/core/src/next/fusion` provides Core's Fusion processors. Treat these as package implementation, not host configuration: change Earth-owned build behavior in root `../../../vite.config.mts` or `fusionfile.ts`, and follow the package guidance before changing vendor code.

For the runtime side—how Core's `AssetService` resolves and renders assets with or without Vite—see the [AssetService and Vite reference](../docs/asset-service.md).
