# E2E / Playwright 測試指引

## 目的

在真實瀏覽器中，針對本機開發伺服器驗證需要實際互動的前端行為（表格工具列按鈕、對話框、表單）。

需要登入的 Windwalker 流程（認證檔案、session cookie、session 橋接等）請見 @e2e-windwalker-auth.md。

## 前置條件

- 使用 `playwright-core` 直行測試。它不會下載瀏覽器；請以 `channel: 'chrome'` 啟動已安裝的 Chrome（Edge 則使用 `channel: 'msedge'`）。若缺少套件，請提示使用者。
- 腳本與日誌放在 `tmp/e2e/`（已被 git 忽略）。請在 repo 根目錄執行：`node tmp/e2e/<script>.mjs`。
- 如果在開始測試前，需要啟動 PHP dev server or JS dev server，或者需要 build js (`yarn build`)，請搜尋 instructions 尋找專案內的執行方式。

## 工作流程

- **以明確的連接埠啟動 PHP 伺服器**，並記錄其 PID：

   ```bash
   php windwalker server:start php:main --host localhost --port 8089 \
     > tmp/e2e/server.out 2> tmp/e2e/server.err &
   echo $!                                                        # 伺服器 PID
   curl -s -o /dev/null -w "%{http_code}" http://localhost:8089/  # expect 200
   ```

   回傳的 PID 可能是啟動器而非實際監聽的程序。請用 `Get-NetTCPConnection -LocalPort 8089 -State Listen` 找出真正的 PID，並只停止該程序（`Stop-Process -Id <PID>`）。
- **準備登入（若測試需要）**：見 @e2e-windwalker-auth.md。
- **複製並調整腳本**：將 `../assets` 複製到 `tmp/e2e/`，依專案與測試情境調整後執行。
- **清理**：刪除 `.mjs` 腳本與日誌，並只停止你自己啟動的伺服器。保留 `.env.agent.local`。

## 範例腳本

`../assets` 會登入（使用 `.env.agent.local` 中的 Session ID 或憑證）、開啟後台列表頁、在未勾選任何項目時點擊刪除工具列按鈕，並輸出原生對話框內容。**這只是範例：請依專案與測試情境調整網址、選擇器與斷言。**
