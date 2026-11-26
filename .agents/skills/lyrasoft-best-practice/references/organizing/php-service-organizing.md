# PHP / Windwalker Service 組織方式

## 基本規則

- Service 統一放在 `Service` 目錄，依 Domain 命名（如 `ProjectService`）
- 不封裝單純查詢（如只包一層 `findOne`），只封裝帶特定規則的查詢

## 常用函式命名

- `get*`：回傳最終結果
  - 特定規則的 getter：如 `getProjectByNo(string $no, bool $forUpdate = false)`，`forUpdate` 透過 `ORMOption` 傳入
  - `getXxxOrCreate()`：以 `orm->transaction()` 包住「查無則建立」
  - `getCurrentProject()`、`getProjectByTask()`：使用 `orm->mustFindOne()`
- `query*`：回傳 `Query`，供其他函式再組裝（如 `queryAvailableProjects(Organization $org): Query`）
- `count*`：回傳數量（如 `countAvailableProjects()` 呼叫 `query*()->count()`）
- 列表條件：不要只包 `getProjects($conditions)` 的 where；前台常用條件（state、org_id）應封裝
- 使用者可見專案：`getMyAvailableProjects()` 在 `queryAvailableProjects()` 上 `innerJoin` 成員表
- 到期專案：`getExpiredProjects()` 以 `chronos($now)` 比較 `expiry_date`
- 回傳 Collection 的函式加上 `@return Collection<Project>`

### Query 用法

- `query*` 回傳的 Query 可直接 `foreach`，也可繼續 `where` / `order`
- 需要撈出後再依規則過濾時，用 `Generator`（如 `queryExpiredProjects()` 內 `yield`）組成新 iterator

## 注入規則

避免 ProjectService 與 TaskService 互相注入造成循環，以及 `getTasks` 在兩邊重複。

- **從屬關係**：只能由上層注入下層（Task 屬於 Project，由 Project 注入 Task）
- **依附關係**：依賴者注入被依賴者（Order 依賴 Product，由 Order 注入 Product）
- **取從屬項目**：在從屬項目的 service 取得（`queryProjectTasks()` 放在 TaskService）
- **上層聚合**：ProjectService 只在需要聚合時才呼叫 TaskService（如 `getProjectForDashboard()`）
- TaskService 若必須取得所屬 Project，見「大型專案的處理方式」

## 大型專案的處理方式

當 Service 過大（如 ProjectService、ProjectRunnerService、ProjectTaskService 各有 20 幾個方法，或 read / write 混在一起）時，改用角色命名拆分。命名概念類似 Vue 專案，參考 `vue-service-organizing.md`

| 角色 | 職責 | 命名範例 |
| --- | --- | --- |
| Dispatcher | 協調、組合各動作，分派給 Writer 或 Runner | `ProjectDispatcher` |
| Finder / Reader / Selector | 複雜篩選、過濾、正規化讀取 | `ProjectFinder`、`ProjectSelector` |
| Writer / Updater | 複雜資料校驗、關聯寫入 | `ProjectWriter`、`ProjectUpdater` |
| Fetcher | 同 Finder，但讀取遠端 API | `ProjectApiFetcher` |
| Runner | 非同步、排程、耗時背景作業 | `ProjectRunner`、`ProjectTaskRunner` |
| Processor | 處理一項行為的動作、進度等 | `LessonProcessor` |
| Resolver | 將字串正規化或解析為對應項目 | `ShortUrlResolver` |
| Service / Facade / Composer / Orchestrator | 外層聚合多個 service 的組合功能 | `ProjectService`、`ProjectOrchestrator` |
| Handler | 處理單一事件，與 DB 無直接關聯 | `ShareHandler`、`ShortCodeHandler` |
| Validator | 資料驗證 | `PersonalIDValidator` |
| Formatter / Parser | 資料格式轉換或正規化 | `AddressFormatter` |

- 結尾用「動詞 + 角色」或常見名詞；其他常用角色：Adapter、Creator、Accessor、Monitor、Executor、Normalizer、Builder
- 拆分後可解決循環注入：TaskService 注入 `ProjectFinder`，ProjectService 只注入 `TaskFinder`

## DDD 目錄切割

依現實世界實體（Domain）切分目錄。Windwalker 的 MVC Module 本身即採用 DDD 概念。

```
src/
├── Core/
│   ├── Api/          （ApiControllerTrait.php、TokenData.php）
│   └── Auth/         （DatabaseAuthMethod.php、LdapAuthMethod.php、GoogleSocialProvider.php）
├── Features/
│   ├── Project/      （ProjectService、ProjectFinder、ProjectWriter、ProjectDispatcher）
│   └── Task/         （TaskRunner.php）
└── Shared/
    ├── Currency/     （CurrencyFormatter.php、PriceObject.php）
    ├── GitHub/       （GitHubClient.php、GitHubSdk.php）
    └── Utilities/    （TextFormatter.php）
```

- **Core**：全站共用的底層邏輯、Traits、services；少了全站會壞。不直接對應 Entity（如 Api、Auth、Cache）
- **Features**：各功能或 domain 專屬
  - 多數直接對應 Entity，負責 DB 交互與商業邏輯（如 Project、Task、User、Order、Organization）
  - 沒有對應 Entity 但需獨立處理的 domain 也放這裡：如 `EmailNotifyRunner` 批次撈取多個 Entity 發送通知並回存狀態；Notification 含 Email / SMS driver 並寫入 OrderHistory，即使沒有 `notifications` 資料表
- **Shared**：不屬於特定 domain、大家都能用的無狀態功能或雜項工具（如 Currency、GitHub、DateTime、HTML、Utilities）
  - 只影響少數頁面、不會讓所有頁面損壞；如只有 `send()` 且不回寫資訊的 Slack helper
- 原本的 Service 不會全部放 Features，而是拆散到三個目錄
- 約八成 Service 對應 Entity，多數放在 Features；Features 內的項目不一定對應 Entity

## Windwalker 既有目錄（不移動）

因涉及自動產生程式碼，維持原位：

- Component、Cache、Middleware、Entity、Repository、Field、Data、Job
