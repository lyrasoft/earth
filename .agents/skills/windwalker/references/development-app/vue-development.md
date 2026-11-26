# Vue 開發

## 可用項目

- Fusion Next 基於 Vite，Vue 使用官方 `@vitejs/plugin-vue`，不需要另外的 build 任務或 `vue` 目錄。
- Vue 檔放在 `resources/assets/src`，與其他 TypeScript 一起管理。
- 安裝：`yarn add @vitejs/plugin-vue vue`，並在 `vite.config.ts` 的 `plugins` 加入 `vue()`。
- `vue` 別名指向 `vue/dist/vue.esm-bundler.js`（定義於 `fusionfile.ts`），需保留，才能編譯 runtime 模板字串。
- 靜態 HTML 依賴空白時，可設定 `vue({ template: { compilerOptions: { whitespace: 'preserve' } } })`。
- 根元件：`resources/assets/src/app/*.vue`，使用 `<script setup lang="ts">`。
- 掛載：
  - Blade 放置掛載點，例如 `<lesson-edit-app id="lesson-edit-app"></lesson-edit-app>`。
  - 與 view 同名的模組（如 `lesson-edit.ts`）以 `createApp().mount('#lesson-edit-app')` 掛載。
  - 同一 app 會出現在多個 view 時，抽成 `createLessonApp()` 等 factory 函式。
- 傳入 props（使用 Unicorn Data Transfer）：
  - PHP 端注入 `UnicornScript`，以 `$this->unicornScript->data('lesson.edit.props', [...])` 寫入頁面。
  - 同名模組以 `data('lesson.edit.props')` 讀取，並作為 `createApp(App, props)` 的第二個參數。
  - 陣列或物件會以 JSON 寫入頁面原始碼，不要放密碼、API 金鑰或私人資料。
  - PHP 端的 `data()` 第三個參數傳 `false` 可覆寫既有值，預設會合併。
  - JS 端的 `data(name, value)` 只存在瀏覽器，不會回傳 PHP。
  - 詳見 [Data Transfer](https://windwalker.io/documentation/unicorn/javascript-frontend/basics/data-transfer.html)。
- 外掛：在 `mount()` 前以 `app.use()` 註冊。
- 全域 helper：
  - 在 plugin 函式中設定 `app.config.globalProperties.$xxx`。
  - 在 `resources/assets/src/types/shims.d.ts` 以 `ComponentCustomProperties` 宣告型別。
- 型別檢查：Fusion build 不檢查 `.vue`，需另外執行 `npx vue-tsc --noEmit --skipLibCheck`（需先安裝 `vue-tsc`）。`vue-tsc` 只回報錯誤，不影響 `yarn build`。
- 修改 `.vue` 與入口模組後，由 [dev server](https://windwalker.io/documentation/framework/frontend/dev-server.html) 重新載入。

## 去哪找

- 建置入口、別名、輸出路徑與 manifest：@../knowledge/frontend-and-assets.md
- `~` 別名（對應 `resources/assets`）與資產路徑：@../knowledge/frontend-and-assets.md
- 官方原文：[Vue Development](https://windwalker.io/documentation/framework/frontend/vue.html)
- 其他文件查詢：`windwalker-docs-search` MCP 工具
