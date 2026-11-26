# Windwalker App 測試

Host app 的測試從一開始就是整合環境：config、provider 已設定好，不需另外指定 `phpunit.app.xml`，也不需重新組裝 provider。

## 自動重置（所有層級皆適用）

- 有需要 database 的 TestCase 開始時：refresh migration。
  - 只有第一次跑到需要 database 的測試時才會執行 migration。
- 每個 test method 開始前：truncate tables。
- 測試不需自行建表或清資料；也不依賴前一個測試留下的資料。

## 層級

挑選能涵蓋行為的最低層級。1、2 都可能用到 DB。

| Tier | 說明 | 用於 | DB |
| --- | --- | --- | --- |
| 1 單元 | mock 依賴的 service | 單一類別 + test double | 視需要 |
| 2 輕量整合 | 使用 app 中真實的 service 與 DI | request context、middleware、routing、controller dispatch | 視需要 |
| 3 DB：memory | SQLite `:memory:` | 純邏輯 SQL/ORM，不含方言特性 | memory |
| 4 DB：real | 真實 DBMS | 方言行為、migration、seeder | real |

- Tier 3、4 的規則與 trait 見 @database-testing.md。
- Tier 1 的 test double 見 @phpunit-testing.md。

## 輔助（`src/Core/Testing/`）

- `AbstractTestApp`：app 測試基底；`tests/TestApp.php` 是子類。
- `Traits/ServiceAwareTestTrait`：每個測試類別一個 child container；提供 `make()`、`retrieve()`、`call()`；需先備妥 `AbstractTestApp::$ctx`（或 `$sharedCtx`），不負責 boot。
- `Traits/ControllerTestTrait`：`dispatchController()` 接收 controller、input 或 request、方法參數；需先備妥 app context。
- `Traits/InMemoryDatabaseTestTrait`、`Traits/DatabaseTestTrait`：見 @database-testing.md。

## 各層斷言

| 對象 | 斷言 | 層級 |
| --- | --- | --- |
| Enum / 值型別 | case 與對應 | 1 |
| Service / Factory | 回傳值、與協作者的互動 | 1 |
| `AppContext` request API | input、headers、method override、API 判斷、url vars、stage | 2 |
| Middleware | 順序、short-circuit、debug 時例外重拋 | 2 |
| Routing | route 對應並 dispatch 到 handler | 2 |
| Controller dispatch | 注入 `AppContext`、`dispatchWithInput()` 後狀態還原 | 2 |
| Repository / ORM | CRUD、query 結果 | 3 / 4 |
| Migration / Seeder | schema 與資料 | 4 |

## Request 範圍行為

- 每個測試建立新 context，不跨測試重用 `AppContext`。
- 驗證 request-scoped：建立兩個 context，實例應不同；app-scoped service 應相同。
- 用 `createContext($request)` 注入 request，不碰 globals。
- `AbstractTestApp::prepareSharedContext()` 設定 `$sharedCtx`/`$ctx`；`dispatchWithInput()` 會交換並還原 request/controller。
- 測試 context 會停用 debug profiler；只在測 debug 模式錯誤傳遞時設 `'debug' => true`。
- 標記 `#[Isolation]` 的 service 在每個 context 重建，可據此斷言兩個 context 的實例不同。

## View model 與 controller

- View model 直接測：以 double 建構，呼叫 `prepare($appContext, $view)`，分開斷言回傳資料與 `HtmlFrame`/`#[ViewMetadata]`。
- 依賴 input 的 view model：用 Tier 2 context。
- Controller：`dispatchController()`，或 `runContext($ctx, [Controller::class, 'task'])`。
- 預設 task：GET `index`、POST/PUT/PATCH `save`、DELETE `delete`。
- 不在單元測試斷言完整 HTML；layout 與 template 用 Tier 2。

## Fixture

- 測試中不使用其他專案專屬的名稱、route、config。
