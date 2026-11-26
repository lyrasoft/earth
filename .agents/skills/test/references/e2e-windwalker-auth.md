# E2E：Windwalker 登入獲認證方式

本文件說明在 Windwalker 專案中做 Playwright E2E 測試時，如何取得並注入登入狀態。一般的 E2E 流程請見 @e2e-testing.md。

## 選擇登入方式

在第一個需要登入的測試之前：

1. **優先嘗試 `.env.agent.local`。** 若該檔案中已有此站台可用的項目（仍有效的 `TEST_<STAGE>_SESSION_ID`，或 `TEST_<STAGE>_USERNAME` + `TEST_<STAGE>_PASSWORD`），無需詢問直接使用。
2. 否則（檔案不存在、站台欄位為空，或項目失效），**詢問使用者要採用哪種方式**（使用 ask-user 工具，一次一個問題）：
   - **帳號 + 密碼** → 寫入 `.env.agent.local`，透過表單登入。
   - **Session ID** → 寫入 `.env.agent.local`，注入 cookie。
   - **Session 橋接**（透過資料庫為既有使用者偽造 session；需要 `phpunit.xml`／`.env` 的資料庫存取權）→ 見「選項：session 橋接」。
   - **臨時測試使用者**（最後手段）→ 見「備用方案：臨時測試使用者」。
3. 切勿將憑證回傳顯示，或複製到指示、skills、文件或記憶中。

## 認證檔案：`.env.agent.local`

測試的登入狀態存放於 repo 根目錄的 `.env.agent.local`（透過 `.env*.local` 被 git 忽略）。這是 dotenv 檔案（每行 `KEY=value`），使用者可隨時編輯。

```dotenv
TEST_FRONT_USERNAME=
TEST_FRONT_PASSWORD=
TEST_FRONT_SESSION_ID=
TEST_ADMIN_USERNAME=
TEST_ADMIN_PASSWORD=
TEST_ADMIN_SESSION_ID=
```

規則：

1. 若使用者選擇帳號／密碼或 Session ID，只將該項目寫入檔案中對應的站台（`TEST_ADMIN_*` 或 `TEST_FRONT_*`）。切勿自行編造憑證。
2. 之後執行時先讀取該檔案。若已設定 `TEST_<STAGE>_SESSION_ID` 則優先使用；否則以 `TEST_<STAGE>_USERNAME` 與 `TEST_<STAGE>_PASSWORD` 登入。
3. 若儲存的 Session ID 已失效（被導向登入頁），若有帳號密碼則改用；否則重新詢問使用者並更新檔案。
4. 不要讀取使用者瀏覽器的 cookie 或設定檔。只使用使用者提供的資料。

### Session cookie 名稱

| 站台 | Cookie 名稱 | 登入網址 |
| --- | --- | --- |
| 前台 | `WINDWALKER_SESSID` | `/login`（依站台而異） |
| 後台 | `WINDWALKER_ADMIN_SESSID` | `/admin/login` |

開啟頁面前，先使用 `context.addCookies([{ name, value, domain: 'localhost', path: '/' }])` 注入 Session ID。Cookie 在 `localhost` 的不同連接埠間共用，但該 session 必須存在於伺服器使用的同一個 session 儲存中。

### 登入表單

多數狀況下可以參考此登入頁面結構，但若專案有自訂登入頁面，請依實際情況調整或詢問：

- 前台: 欄位 `#input-user-email`、`#input-user-password`；送出按鈕 `#login-form .login-button`；成功後導向 `/`。
- 後台: 欄位 `#input-user-email`、`#input-user-password`；送出按鈕 `#login-form .login-button`；成功後導向 `/admin`。
- 其他專案可能不同，重用選擇器前請先檢查頁面。

### 登入腳本範例

`../assets` 已示範以 Session ID 或帳密登入並進入後台。它只是範例，請依專案調整。

## 使用 curl 快速檢查

`curl` 適合檢查頁面是否回傳 200（而非導向登入頁），但需注意：

- **送出瀏覽器 User-Agent。** `SessionRobotSubscriber` 會對機器人 UA 停用 session，因此預設的 `curl/x.x` UA 即使帶有效 session cookie 也會被視為未登入（已驗證：加入 `-A` 後由 303 → 200）。
- 要避免被視為機器人，請使用真實瀏覽器的 UA，例如：
  - Chrome: `Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36`
  - Edge: `Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36 Edg/120.0`
- Cookie 屬性（domain、path、httponly、samesite）對 `-b "NAME=value"` 沒有影響；它送出的是原始 header。
- 在 PowerShell 中使用 `curl.exe`；存取 `/admin/*` 時使用後台 cookie 名稱 `WINDWALKER_ADMIN_SESSID`。

```bash
curl -s -o NUL -w "%{http_code} %{redirect_url}`n" `
  -A "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36" `
  -b "WINDWALKER_ADMIN_SESSID=$id" http://localhost:8089/admin/article/list
```

點擊、對話框與 JS 行為仍需使用 Playwright。

## 選項：session 橋接（為既有使用者偽造 session）

適用於使用者無法提供 Session ID，且不希望使用密碼登入的情況。`../assets` 會為**既有**使用者寫入一筆 session 資料列（絕不變更密碼）、輸出其 ID，並於事後刪除。

1. 將其複製到 `tmp/e2e/session-bridge.php`，並依需要調整（根目錄路徑、資料庫驅動、使用者 email）。
2. `php tmp/e2e/session-bridge.php create <email> admin` → 取得 session ID；將其放入 cookie（後台使用 `WINDWALKER_ADMIN_SESSID`），或放入 `.env.agent.local` 的 `TEST_<STAGE>_SESSION_ID`。
3. 完成後：執行 `php tmp/e2e/session-bridge.php destroy <session_id>`，清空 `.env.agent.local` 中的 `TEST_<STAGE>_SESSION_ID` 值，並刪除複製的腳本。

已驗證的事實：儲存位置依循 `SESSION_DEFAULT`（`.env.base` 將 `database` 設為 `sessions` 資料表，欄位為 `id, data, user_id, stage, time`；預設會是 `<tmp>/sess/sess_<id>`）。資料內容為 `serialize(['login_user_id' => <int>])`；也應設定 `user_id` 與 `stage`（`admin`／`front`）。使用者必須 `enabled=1`、`verified=1`，且具有 superuser／admin 角色。資料庫設定來自 `phpunit.xml` 的 `WINDWALKER_TEST_DB_DSN`（優先；解析出 host／dbname／user／password），若無則回退至 `.env.base`／`.env.dev`／`.env` 的 `DATABASE_*`。請確認 DSN 的 `dbname` 是應用程式資料庫，而非框架的測試資料庫。切勿印出密碼。偽造的 cookie 在 Playwright／Chrome 中可用；純 `curl` 會被導向登入頁，因此請在瀏覽器中驗證。

## 備用方案：臨時測試使用者

若使用者無法提供憑證或 session，可在資料庫中建立臨時使用者（連線資訊取自使用者的 `phpunit.xml`／`.env`，僅用於連線，切勿印出密碼）。事後刪除；不要修改既有後台管理者的密碼雜湊。需求如下：

- 新增一筆 `user_roles` 資料，角色為 **superuser**（優先於 `admin`，因為各專案的權限差異很大）。進入後台至少需要 `admin` 或 `superuser`。
- `enabled` 必須為 `1`，`verified` 必須為 `1`。
- 新建的使用者可能沒有自己的資料（個人資料、關聯紀錄）；請準備頁面所需的資料。
- 於執行時以 PHP（`password_hash`）產生密碼雜湊；不要將固定雜湊存入 repo。
- 將新帳號像其他憑證一樣寫入 `.env.agent.local`（`TEST_<STAGE>_USERNAME` ／ `TEST_<STAGE>_PASSWORD`），完成後再移除該使用者。

## 相關文件

- [Session 元件](https://windwalker.io/documentation/components/session/index.md)
- [Session 處理器（Handlers）](https://windwalker.io/documentation/components/session/handlers.md)
- [Session 橋接（Bridges）](https://windwalker.io/documentation/components/session/bridges.md)
- [Session 與 Cookie](https://windwalker.io/documentation/framework/core-concepts/state-control/session-and-cookies.md)
