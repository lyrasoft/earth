# Vite 與 Fusion 資產

## Fusion 建置

- 主機的 `vite.config.mts` 透過 `useFusion(() => import('./fusionfile'))` 載入 `fusionfile.ts`。建置的入口、任務、別名與輸出路徑都在 `fusionfile.ts` 中設定。
- 輸出目錄：`www/assets/`（`fusion.outDir()`），manifest 為 `www/assets/manifest.json`。
- 別名：
  - `~` → `resources/assets`
  - `~js` → `resources/assets/src`
  - `~vendor` → `www/assets/vendor`
  - `vue` → `vue/dist/vue.esm-bundler.js`
- 任務與輸出：
  - `js()`：`resources/assets/src/front/main.ts` → `js/front/main.js`；`resources/assets/src/admin/main.ts` → `js/admin/main.js`
  - `css()`：`scss/front/bootstrap.scss` → `css/front/bootstrap.css`；`scss/front/main.scss` → `css/front/main.css`；`scss/admin/main.scss` → `css/admin/main.css`
  - `images()`：`resources/assets/images/**/*` → `images/`
  - `install()`：使用 `installVendors()` 安裝 `@fortawesome/fontawesome-free` 到 `www/assets/vendor/`。
- 模組內的 `assets/` 目錄與 Blade 內嵌的 script／style 會由 `jsModulizeDeep()`、`cssModulizeDeep()` 納入對應的 stage。
- 指令：`yarn dev`（Vite 開發伺服器，不產生靜態建置）、`yarn build`、`yarn build:prod`、`yarn watch`。

參考：

- [Setup and Build | Getting Started](https://windwalker.io/documentation/framework/frontend/setup.md)
- [Styles and Assets](https://windwalker.io/documentation/framework/frontend/styles-and-assets.md)
- [Frontend Integration | Getting Started](https://windwalker.io/documentation/framework/frontend/overview.md)

## 資產路徑與輸出

- 請編輯 `resources/assets/` 或模組 `assets/` 中的原始檔，不要編輯 `www/assets/` 中產生的檔案。
- 以 `~/` 開頭的路徑為 Vite 別名，需同時在 `fusion.alias()` 與 `tsconfig.json` 的 `paths` 中設定。
- 固定入口的輸出路徑穩定；其匯入的 chunk 可能帶雜湊值，位於 `www/assets/chunks/`。圖片與字型等被參照的資產可能輸出到 `images/`、`fonts/`。
- 追蹤來源與實際輸出的對應，請查 `www/assets/manifest.json`。不要猜測雜湊值。
- 清理輸出時，不要清空整個 `www/assets/`，需保留 `upload/`、`vendor/` 等非建置產物。

參考：

- [Asset Management | Views](https://windwalker.io/documentation/framework/core-concepts/views/asset-management.md)

## 執行期解析（AssetService）

- `AssetService` 負責註冊與渲染 CSS／JS 連結，解析 URL，不會編譯原始檔。
- 使用 `$asset->path('~/images/logo.png')` 取得資產 URL，或 `$asset->root('~/images/logo.png')` 取得完整 URL。
- 一般 Layout 或 Blade 模板中，接會自動載入 `$asset`，在 service 中可以手動注入 `AssetService`。
- `ViteResolver` 透過兩種訊號運作：
  - 開發伺服器檔案（預設 `tmp/vite-server`）：存在時解析為伺服器 URL，並在 JS 中加入 `@vite/client`。
  - Vite manifest（預設 `@public/assets/manifest.json`，本主機即 `www/assets/manifest.json`）：將來源入口對應到輸出的 `file` 與 CSS。
- `~/` 會正規化為 `@vite/`，基底預設為 `resources/assets`。
- `viteEntry()` 會註冊 JS 入口，並加入 manifest 中該入口的 CSS。
- 無 Vite 伺服器也無 manifest 時，Vite 別名不會解析，URI 交由一般資產處理。
- 一般本地 URL（如 `css/site.css`）不受 Vite 影響，仍走一般路徑解析。
- 本地檔案查找：除錯模式優先未壓縮檔，正式模式優先 `.min` 檔，缺少時回退到另一種。
- `has()` 檢查本地資產是否存在，回傳解析後的 URL 或 `false`。渲染時不檢查檔案是否存在。

參考：

- [Asset Management | Views](https://windwalker.io/documentation/framework/core-concepts/views/asset-management.md)

## 設定與疑難排解

- 設定命名空間為 `asset`：
  - `asset.folder`：預設 `assets`
  - `asset.uri`：覆寫系統 URI 路徑
  - `asset.vite.base`：預設 `resources/assets`
  - `asset.vite.manifest`：預設 `@public/assets/manifest.json`
  - `asset.vite.server_file`：預設 `tmp/vite-server`
- 資產遺失或過期時：
  1. 確認 URI 是一般 URL，還是 Vite 別名（`@vite/...`、`~/...`）。
  2. Vite 別名：檢查開發伺服器檔案；否則查 manifest 的來源鍵與輸出的 `file`。
  3. 一般 URL：檢查公開資產資料夾，以及一般與 `.min` 檔案，並確認版本控制設定。
  4. HTML 中仍無正確 URL 時，檢查 `AssetBeforeRender` 事件監聽器，以及 CSS 是否註冊為 footer 內容。

參考：

- [Asset Management | Views](https://windwalker.io/documentation/framework/core-concepts/views/asset-management.md)
- [Package JavaScript | Package Development](https://windwalker.io/documentation/framework/packages/package-javascript.md)
