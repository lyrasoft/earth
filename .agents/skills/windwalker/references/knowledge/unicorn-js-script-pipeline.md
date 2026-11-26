# Unicorn JS Pipeline 與 PHP Adapter 運作方式

本文說明 Windwalker Unicorn 前端的 JS 載入流程，以及 PHP 端如何把資料與腳本交給前端。

## 整體流程

- PHP 端的 Script 物件（例如 `UnicornScript`、`BootstrapScript`、`FormScript`、`EditorScript`）負責宣告要載入的前端入口。
- `init()` 會註冊 `@vite` 入口。依模式不同：
  - next 模式：輸出 import map，以 ES module 載入。
  - classic 模式：使用 SystemJS 載入。
- `main()` 會把 URI 資料、翻譯與 CSRF token 等資料寫入頁面，供前端讀取。
- 前端入口（`@main`）透過 `importMainThen()`、`importThen()`、`importScript()`、`addInitialise()` 等 helper 載入模組並執行初始化。
- Helper 在每個 script 物件中只會執行一次，並在同一個請求內共用。

## 前端 App 與 Adapter

- `useUnicorn()` 回傳單例（singleton）應用程式，提供：
  - 事件：`on`、`once`、`off`、`trigger`
  - 依賴注入：`provide`、`inject`、`use`
  - 資料：`data()`、`useMacro`
- `loaded` 事件在 DOMContentLoaded 後只觸發一次。
- 入口檔中呼叫 `useUnicornPhpAdapter()`，即可建立與 PHP 端的連接。
- `pushUnicornToGlobal()` 會把 `u` 暴露給 inline script 使用。
- 透過 `useImport()` 載入套件時，需要 import map 支援。
- 同一個套件不要同時使用 `import` 與 `useImport()`，否則會重複載入。

## 資料傳遞

- PHP 端只會把資料寫入一次，寫入 `document.__unicorn`，且在頁面腳本執行前完成。
- 在渲染頁面的類別中注入 `UnicornScript`，同一請求共用同一個實例。
- PHP 端的寫入方法：
  - `data(name, value)`：儲存任意值。同名時預設合併；第三個參數傳 `false` 會取代。
  - `translate('key')` 或 `translate('prefix.*')`：複製翻譯字串到 `unicorn.languages`，只複製頁面需要的 key。
  - `addRoute(name, path)`：註冊具名網址。
  - `exposeCspNonce()`：使用 CSP nonce 時呼叫，nonce 會以 `data('csp-nonce')` 讀取。
- JS 端讀取：
  - `data(name)`：讀取資料，例如 `data('user.tags')`；CSRF token 為 `data('csrf-token')`。
  - `trans(key)`：讀取翻譯字串。
  - `route(name, query)`：產生路由網址，名稱未註冊時會拋出錯誤。
  - `useSystemUri(part)`：取得 `full`、`path`、`root`、`current`、`route` 或 `script`。`useAssetUri()` 讀取資產路徑。
- JS 端以 `data(name, value)` 寫入的值只留在瀏覽器，不會回傳給 PHP。
- 這些資料都會以 JSON 出現在頁面原始碼中，任何能載入頁面的人都能讀取，因此不要放入密碼、API 金鑰或私人記錄。

範例（PHP）：

```php
$this->unicornScript->translate('user.edit.*');
$this->unicornScript->addRoute('user_delete', '/admin/user/delete');
$this->unicornScript->data('user.tags', $selectedTags);
```

範例（JS）：

```ts
import { data, route, trans } from '@windwalker-io/unicorn-next';

const tags = data('user.tags');
const label = trans('user.edit.save');
const url = route('user_delete', { id: 5 }); // /admin/user/delete?id=5
```

## 主題與 UI Adapter

- Unicorn 的版面透過 `@theme` 別名解析，預設主題為 `views/ui/bootstrap5`。
- Bootstrap JS 透過 `useUITheme()` / `UIBootstrap5` 載入。
- 自訂 adapter 需要實作 `renderMessage()` 與 `clearMessages()`。

## 編輯器

- `useTinymce(selector)` 與 `useNuxtEditor(selector)` 會回傳控制器，提供 `getValue`、`setValue`、`insert`、`setEditable`。
- 同一個 selector 重複呼叫會得到同一個控制器。
- TinyMCE 的選項會合併到 `tinymce.init()`。
- 上傳事件為 `upload-start`、`upload-success`、`upload-error`、`upload-complete`。
- `TinymceEditorField` 繼承 `TextareaField`：
  - 工具列預設有 `full` 與 `simple` 兩種。
  - 可透過 `editorOptions()`、`uploadProfile()`、`imageUploadUrl()`、`stackName()`（預設 `uploading`）調整。
  - 伺服器端不會清理 HTML，需自行處理。
- `NuxtEditorField` 基於 TipTap / Nuxt UI `UEditor`：
  - 格式可為 `html`、`markdown`、`json`。
  - 透過 `NuxtFieldOptions` 與 `configureOptions()` 設定，建立時會觸發 `nuxt-editor:create`。

## 參考連結

- [Script 載入](https://windwalker.io/documentation/unicorn/services/script-loading.md)
- [Unicorn JS](https://windwalker.io/documentation/unicorn/javascript-frontend/unicorn-js.md)
- [資料傳遞](https://windwalker.io/documentation/unicorn/javascript-frontend/basics/data-transfer.md)
- [翻譯](https://windwalker.io/documentation/unicorn/javascript-frontend/utilities/translation.md)
- [主題設定](https://windwalker.io/documentation/unicorn/introduction/theming.md)
- [編輯器](https://windwalker.io/documentation/unicorn/javascript-frontend/ui/editors.md)
- [TinyMCE 編輯器欄位](https://windwalker.io/documentation/unicorn/form-fields/field-types/tinymce-editor.md)
- [Nuxt 編輯器欄位](https://windwalker.io/documentation/unicorn/form-fields/field-types/nuxt-editor.md)
