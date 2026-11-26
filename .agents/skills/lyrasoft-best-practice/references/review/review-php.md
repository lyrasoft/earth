# PHP 程式碼審查指引

審查程式碼變更、PR 或 diff 時，請遵守以下規則。

## 審查優先順序

依下列順序檢查：

1. 正確性：邏輯錯誤、邊界條件、null／空值處理、off-by-one、競態條件。
2. 安全性：輸入驗證、授權檢查、SQL／XSS／注入風險、機敏資訊外洩。
3. 專案規範：`.github/instructions/` 內的模式（PHP DDD 結構、Nuxt 結構、路由、DTO、enum）。
4. 測試：新行為有 `tests/` 下的測試；既有測試仍符合變更後的行為。
5. 風格：PHP 遵循 PSR-12，前端遵循 Airbnb ESLint。

## PHP 型別反射性

檢查 PHP 程式碼是否遺失型別資訊。若 IDE 或靜態分析無法推斷型別，請建議修正。

### 複雜陣列、物件與 `json_decode` 結果

當資料為多欄位陣列、物件或解碼後的 JSON 時，建議補上 `@param`、`@return` 或 `@var` 的 shape。

```php
/**
 * @param array{
 *     id: int,
 *     name: string,
 *     tags?: list<string>,
 *     meta: array{created_at: string, source: 'api'|'web'}
 * } $payload
 */
function importPayload(array $payload): void
{
}

/**
 * @return array{id: int, name: string}|null
 */
function decodeUser(string $json): ?array
{
    /** @var array{id: int, name: string}|null $data */
    $data = json_decode($json, true);

    return $data;
}
```

### 函式簽名中的 `mixed` 與 `array`

建議改用具體的型別宣告或 generics，取代 `mixed` 或單純的 `array`。

```php
/**
 * @template T of object
 * @param class-string<T> $class
 * @return T
 */
function make(string $class, mixed ...$args): object
{
    return new $class(...$args);
}

/**
 * @param list<User> $users
 * @return array<int, string> User names keyed by ID
 */
function mapUserNames(array $users): array
{
    $result = [];

    foreach ($users as $user) {
        $result[$user->id] = $user->name;
    }

    return $result;
}
```

### 跨變數傳遞的 Entity

當 Entity 經由陣列、`compact()`、序列化或無型別變數傳遞而遺失型別時，建議在類別層級加上 PHPDoc 宣告其結構。

```php
/** @var Keyword $item */
$item = $someDynamicActions();

// OR
/** @var ?Category $category */
$category = $orm->tryEntity($item->category);

// OR

/** @var Tag[] $tags */
$tags = array_maps($orm->toEntity(...), $item->tags); 
```

## 專案專屬檢查

- 任何修改、刪除或提交 `.env` 的變更都要標記，屬於阻擋問題。
- 標記已提交的憑證、API key 或 token（例如 `CLERK_SECRET_KEY`）。
- 標記混入 diff 的無關變更。
- 標記重述明顯程式碼的註解。註解只應說明不明顯的邏輯。
- 標記新的前端程式碼依賴 Nuxt auto-import，因為本專案不使用該功能。

## SQL 審查

審查程式碼與 migration 中的 SQL 效能。建議新增索引前，先檢查 `resources/migrations/` 內的索引與鍵。

- 確認該欄位尚未被既有索引、主鍵或唯一鍵涵蓋。索引前綴也算數（例如 `addIndex(['user_id', 'role_id'])` 已涵蓋 `user_id`）。
- 當查詢同時使用 `WHERE` 與 `ORDER BY` 時，建議複合索引：等值條件欄位在前，範圍或排序欄位在後。
- 標記大表上的 `SELECT *`，以及沒有 `LIMIT` 的無上限查詢。
- 標記在 `WHERE` 中對索引欄位套用函數（例如 `DATE(created)`），因為會導致無法使用索引。
- 標記前導萬用字元的 `LIKE '%x'`，以及跨不同欄位的 `OR`，這類寫法通常無法使用索引。
- 標記迴圈中的 N+1 查詢模式。
- 需要新增索引時，指出應修改的 migration 檔案，並沿用專案的 `$schema->addIndex()` 或 `$schema->addUniqueKey()` 寫法。
- 效能問題用 Low 的嚴重度標記，因為索引可以後續補上。若是邏輯錯誤或資料完整性問題，則使用 High 或 Medium。

```php
// Existing: addUniqueKey(['user_id', 'org_id'])
// Query: WHERE org_id = ? AND user_id = ? ORDER BY created DESC
// Suggest: addIndex(['org_id', 'user_id', 'created'])
```

