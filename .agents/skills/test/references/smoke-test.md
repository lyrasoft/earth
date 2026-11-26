# Smoke Test 與暫存執行檔

## 適用範圍

- 用途：單元測試之外的冒煙測試，或驗證應用程式／package 行為的暫存執行檔
- 需要宿主應用程式的 runtime 或服務時使用
- PHPUnit 撰寫與覆蓋率規則：見測試指引

## 暫存檔案

- 位置：repository 的 `tmp/`（勿放在根目錄或 `www/`）
- 忽略規則：`tmp/.gitignore`（保留目錄設定檔）
- 檔名：使用獨特名稱，例如 `tmp/smoke-schedule.php`
- 清理：只刪除本次檢查建立的檔案，勿清空整個 `tmp/`

## 指令探索與產生

- 執行位置：repository 根目錄
- 指令列表：`php windwalker list`、`php windwalker help exec`、`php windwalker g`
- 產生器說明：`--about`（勿用 `-h`，那是外層 generator 的說明）
- command 預設輸出：`src/Command/`；`--ns` 只改 namespace，`--dir` 改輸出目錄
- 自動註冊：`--auto-register <name>`，寫入 `resources/registry/commands.php`
- 非互動：加上 `--no-interaction`
- 避免使用 `--force` 覆寫既有指令
- 產生的指令實作 `CommandInterface`；`execute()` 接收 `IOInterface`；成功回傳 `0`
- 清理：刪除產生的類別與其註冊項目，勿刪 `resources/registry/commands.php`

## 自訂腳本

- 定義：`resources/registry/scripts.php`（由 `etc/app/console.config.php` 載入）
- 列出：`php windwalker run`
- 執行：`php windwalker run <scriptName>`
- 執行前閱讀定義；`preparedev` 會重設 migration 並匯入種子資料
- 一次性檢查優先使用既有入口點；必要時用唯一名稱暫時註冊，事後只移除該項目

## REPL 與 PHP 執行（`exec`）

- 指令：`php windwalker exec`（PsySH）、`--code '<expr>'`、`<path>`（檔案模式）
- 可用變數：`$app`（應用程式）、`$io`（console 輸入輸出）
- 服務與設定：`$app->service(...)`、`$app->config(...)`、`$app->path(...)`
- 檔案模式：以位置參數傳入路徑，非 `--file`；PHP 檔以 `<?php` 與 `declare(strict_types=1);` 開頭；勿在檔內重新啟動應用程式
- 已知問題：`src/Core/Command/ReplCommand.php` 的檔案模式 include 了未定義的 `$real`，修正前勿當作驗證方式
- `--code` 會先求值運算式，失敗後再以陳述式重試，可能導致執行兩次；寫入或需要受控錯誤處理的檢查請改用指令

## 執行期邊界

- 針對宿主應用程式的真實服務與環境，非 PHPUnit fixtures
- 資料庫、佇列、外部服務需可用；寫入前確認目標環境
- 未經授權，勿重設或匯入資料庫種子
- console 不是 web 請求；`src/Console/Application.php` 預設不啟用 web simulator
- 需要 HTTP、路由、session 等狀態時，改用正確的 web 入口點
- 檢查前定義成功與失敗條件；使用有界限、非互動且具明確結束碼的指令
- 單靠輸出或 dump 不足以證明通過
- 常駐 worker 或伺服器：限制執行時間，只停止本次啟動的程序，並清理暫存檔、registry 項目與產物
- 前置條件缺失或檢查不完整時明確回報，勿當作通過
- 冒煙測試是 PHPUnit 的補充，不能取代；package 回歸測試放在該 package 的測試套件

## Web 與渲染冒煙測試

- 適用：需要真實 HTTP 請求、請求範圍服務或渲染 HTML
- 臨時元件：controller 與 route；行為屬於請求管線時再加 middleware
- 必須放在一般應用程式位置才能參與 autoload 與路由（可能出現在 Git 狀態中）
- 這裡指的是靜態 HTML 渲染測試，若需要 E2E 測試請見 @e2e-testing.md

產生器說明：

- `php windwalker g controller --about`、`g middleware --about`、`g view --about`
- controller 預設 `src/Module/`、`App\Module`；帶 stage 的名稱（如 `'Front\Smoke\SmokeController'`）可避免 stage 提示
- middleware 預設 `src/Middleware/`、`App\Middleware`
- 產生檔案本身不會註冊到請求管線

路由註冊：

- 參照相鄰路由定義；`routes/main.route.php` 載入前後台群組
- 前台：`routes/front/*.php`、`routes/custom/front/*.route.php`
- 後台：`routes/admin/*.php`、`routes/custom/admin/*.route.php`（URL 前綴 `/admin`）
- 範例：在 `routes/front/` 放置 `$router->get('smoke_check', '/__smoke-check')->handler(SmokeController::class);`
- view model：`->view(...)`，參照 `src/Module/Front/Home/HomeView.php`（`#[ViewModel]`、`#[ViewPrepare]`、模組內 layout）
- 探測用 middleware：`->middleware(SmokeProbeMiddleware::class)`
- 路由前行為：檢視 `etc/app/main.config.php` 的 `middlewares` 與合併後的設定；保留 `RoutingMiddleware`

驗證真實請求：

- 以明確 port 啟動伺服器（見 CLI 指引），再用 HTTP 客戶端或瀏覽器請求
- 正常入口：web 入口；開發模式：`/dev.php`（會引入 `www/index.php`）
- 檢查 HTTP 狀態、標頭與內容
- 跟隨重新導向前先檢視：標準化網址、維護模式、語系、後台驗證都可能提前攔截
- 前台 middleware 會載入設定與資產，可能需要資料庫與前端資產
- 勿為了通過而繞過驗證或 CSRF 保護
- 依賴 JavaScript 或 CSS 時，改在瀏覽器中檢查 console 與 network 錯誤

清理與安全：

- 端點僅限本機、生命週期短暫
- 勿透過端點暴露機密、任意程式碼執行或破壞性操作
- 移除 controller、middleware、route、view／layout 與產生的資產或註冊項目
- 保留無關檔案，只停止本次啟動的伺服器程序

## 相關文件

- [Console 基本指令](https://windwalker.io/documentation/framework/console/basic-commands.md)
- [Console 伺服器指令（server）](https://windwalker.io/documentation/framework/console/commands/server.md)
- [執行程式碼（exec / run）](https://windwalker.io/documentation/framework/console/commands/running-code.md)
- [Code Generator（產生器）](https://windwalker.io/documentation/framework/console/commands/code-generator.md)
- [請求生命週期](https://windwalker.io/documentation/framework/core-concepts/applications/request-lifecycle.md)
- [Middleware](https://windwalker.io/documentation/framework/core-concepts/http-routing/middleware.md)
- [路由（Routing）](https://windwalker.io/documentation/framework/core-concepts/http-routing/routing.md)
- [測試服務（Testing）](https://windwalker.io/documentation/framework/services/testing.md)
