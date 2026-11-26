# 資料庫測試

撰寫會接觸真實 `DatabaseAdapter` 或 ORM 的測試時的指引，例如 Repository 的 CRUD 方法
（`save`、`getItem`、`mustGetItem`、`delete`）。

專案依賴 `windwalker/core`，因此可以直接在 unicorn 測試中使用 core 的 traits。

## 選擇策略

| 需求 | 使用 |
| --- | --- |
| 純邏輯，不需要資料庫 | Mocks/stubs（`createStub()` / `createMock()`） |
| 真實 SQL/ORM 行為，不需要 host app，也不需要 DSN | `InMemoryDatabaseTestTrait`（SQLite `:memory:`） |
| 特定方言行為（MySQL/PostgreSQL） | Core 的 `DatabaseTestTrait`，搭配專用的 DSN |

SQLite 與 MySQL 或 PostgreSQL 並非完全等價。當測試依賴特定方言的 SQL 或 DDL 時，
請使用外部 DBMS。

## 記憶體內測試（純邏輯）

適用範圍: 純邏輯操作，不帶有特殊 SQL features（例如 MySQL 的 `ON DUPLICATE KEY UPDATE`、PostgreSQL 的 `RETURNING`），或 Schema 變動遷移等。

使用 `Windwalker\Core\Testing\Traits\InMemoryDatabaseTestTrait`。它會在每個測試前
開啟一個全新的 `sqlite::memory:` 連線，並在測試後中斷連線。
覆寫 `prepareInMemoryDatabaseSchema()` 以建立資料表與植入資料列。

```php
use Windwalker\Core\Testing\Traits\InMemoryDatabaseTestTrait;
use Windwalker\Database\DatabaseAdapter;

class ExampleRepositoryTest extends TestCase
{
    use InMemoryDatabaseTestTrait;

    protected function prepareInMemoryDatabaseSchema(DatabaseAdapter $db): void
    {
        $db->execute('CREATE TABLE examples (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT)');
    }
}
```

使用 `setDb($this->db)` 將連線注入 Repository，可避免 `DatabaseRepositoryTrait::getDb()`
的容器查詢。

## 真實 DBMS 測試（需主動啟用）

適用範圍: 大多數情況下，測試需要真實的資料庫連線，並且需要測試特定方言的 SQL 行為，它可能會在每個 test case 刪除並重建已設定的資料庫。

規則：

- 使用專用、可拋棄的資料庫。絕對不要使用共享、staging 或正式環境的資料庫。
- 透過 `phpunit.xml` 中的 `WINDWALKER_TEST_DB_DSN_*` 設定 DSN。預設保持註解狀態，
  讓測試套件保持為需主動啟用。
- 絕對不要在同一個資料庫平行執行資料庫測試套件。
- 藉由 env 控制，每個 agent 可以用後綴 Rand ID 來建立自己的資料庫，避免平行測試互相干擾。

使用 `Windwalker\Core\Testing\Traits\DatabaseTestTrait`：需 `AbstractTestApp` 子類（如 `TestApp`）、migration、app ORM；會 drop 並重建設定的 DB，只用於專用可拋棄 DB。

## 基於 Mock 的測試

基於 mock 的測試應放在 `Unit` 測試套件中。只需要回傳值時使用 `createStub()`；需要驗證
互動時，使用 `createMock()` 搭配 `expects()`。在 PHPUnit 13up 中，`->with()` 必須接在
`->expects(...)` 之後，且 `createStub()` 不支援 `->with()`。

## 相關文件

- [測試](https://windwalker.io/documentation/framework/services/testing.md)
- [資料庫測試](https://windwalker.io/documentation/components/test/database.md)
- [Repository](https://windwalker.io/documentation/unicorn/services/repository.md)
- [資料庫入門](https://windwalker.io/documentation/framework/services/database/getting-started.md)
- [Test 元件](https://windwalker.io/documentation/components/test/index.md)
