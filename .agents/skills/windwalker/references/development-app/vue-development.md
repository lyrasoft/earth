# Vue Development

## Available options

- Fusion Next is based on Vite and uses the official `@vitejs/plugin-vue`; no separate build task or `vue` directory is required.
- Put Vue files in `resources/assets/src` and manage them alongside the other TypeScript files.
- Install with `yarn add @vitejs/plugin-vue vue`, then add `vue()` to the `plugins` in `vite.config.ts`.
- The `vue` alias points to `vue/dist/vue.esm-bundler.js` (defined in `fusionfile.ts`); keep it so runtime template strings can be compiled.
- If static HTML depends on whitespace, set `vue({ template: { compilerOptions: { whitespace: 'preserve' } } })`.
- Root components: `resources/assets/src/app/*.vue`, using `<script setup lang="ts">`.
- Mounting:
  - Add a mount point in Blade, such as `<lesson-edit-app id="lesson-edit-app"></lesson-edit-app>`.
  - A module with the same name as the view (for example, `lesson-edit.ts`) mounts it with `createApp().mount('#lesson-edit-app')`.
  - If the same app is used in multiple views, extract a factory function such as `createLessonApp()`.
- Passing props (using Unicorn Data Transfer):
  - Inject `UnicornScript` on the PHP side and write to the page with `$this->unicornScript->data('lesson.edit.props', [...])`.
  - Read it in the module with `data('lesson.edit.props')` and pass it as the second argument to `createApp(App, props)`.
  - Arrays and objects are written to the page source as JSON; do not include passwords, API keys, or private data.
  - Pass `false` as the third argument to PHP's `data()` to overwrite an existing value; values are merged by default.
  - Values written with JS `data(name, value)` exist only in the browser and are not sent back to PHP.
  - See [Data Transfer](https://windwalker.io/documentation/unicorn/javascript-frontend/basics/data-transfer.html).
- Plugins: register them with `app.use()` before `mount()`.
- Global helpers:
  - Set `app.config.globalProperties.$xxx` in a plugin function.
  - Declare the type in `resources/assets/src/types/shims.d.ts` using `ComponentCustomProperties`.
- Type checking: Fusion build does not check `.vue` files; run `npx vue-tsc --noEmit --skipLibCheck` separately (install `vue-tsc` first). `vue-tsc` only reports errors and does not affect `yarn build`.
- After modifying `.vue` files and entry modules, reload with the [dev server](https://windwalker.io/documentation/framework/frontend/dev-server.html).

## Where to look

- Build entries, aliases, output paths, and manifest: `../knowledge/frontend-and-assets.md`
- The `~` alias (maps to `resources/assets`) and asset paths: `../knowledge/frontend-and-assets.md`
- Official documentation: [Vue Development](https://windwalker.io/documentation/framework/frontend/vue.html)
- For other documentation searches, use the `windwalker-docs-search` MCP tool.
