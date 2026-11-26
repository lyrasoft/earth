# ORM 與 DB 操作最佳實務

## 查詢結果群組與型別化

### 盡量使用自動群組

盡量使用 ORM 的 `groupByJoins()` 將所有 joins 欄位群組成子物件

```php
$items = $orm->from(Item::class)
    ->leftJoin(Foo::class, 'foo', 'foo.item_id', 'item.id')
    ->groupByJoins()
    ->all(Item::class);

foreach ($items as $item) {
    $foo = $item->foo; // Foo 物件 (Collection::class)
}
```

文件 [ORM Querying](https://windwalker.io/documentation/components/orm/querying.md)

### 盡量將物件轉成 Entity 或宣告型別

群組的子物件可以在迴圈內 hydrate 成 Entity

````php
foreach ($items as $item) {
    $foo = $item->foo; // Foo 物件 (Collection::class)
    $foo = $orm->toEntity($foo, Foo::class); // Foo Entity
    $foo = $orm->tryEntity($foo, Foo::class); // Foo Entity or NULL
}
````

從模版引用時，預先宣告型別，讓 IDE 未來可追蹤：

```blade
<?php
/** @var Foo $foo */
?>
{{ $foo->id }}
```

文件 [ORM Entities](https://windwalker.io/documentation/components/orm/entities.md)

## Join 查詢最佳實務

### 避免迴圈 N+1 查詢

列表頁每個 Item 需要多筆 Foo 時，**批次撈取**，不要逐筆查詢。

```php
// Bad：動態插入屬性，或在迴圈內逐筆查詢（N+1）
foreach ($items as $item) {
    $item->foos = $this->orm->from(Foo::class)->where('item_id', $item->id)->all(Foo::class)->dump();
}

// OK：ViewModel 提供取值方法，僅適合少量資料
// ViewModel::getFoos(Item $item): array
//   $this->orm->from(Foo::class)->where('item_id', $item->id)->all(Foo::class)->dump()
// Blade：@foreach ($items as $item) 內呼叫 $vm->getFoos($item)

// Good：收集 ids 一次撈出，依 itemId 分組（一頁建議不超過 50 筆）
$ids = $items->column('id')->dump();

$fooGroup = $this->orm->from(Foo::class)
    ->where('item_id', $ids, [0]) // 避免空陣列產生 IN ()
    ->order('foo.ordering', 'ASC')
    ->groupByJoins()
    ->all(Foo::class)
    ->groupBy('itemId');
```

Blade 中直接取用：

```html
<?php $foos = $fooGroup[$item->getId()] ?? []; ?>
@foreach ($foos as $foo)
    <span class="badge bg-primary">{{ $foo->getTitle() }}</span>
@endforeach
```

注意：批次撈取需要事先取得全部 items，不適用於 iterator。

## 避免重複資料

優先順序：資料庫 Unique Key（最可靠、效能最高）> Entity `beforeSave` 檢查（可加入額外條件）。

- Unique Key 允許空值時，欄位要設為 NULLABLE，不可用空字串（NULL 不會被視為重複）。
- 多對多 mapping 表：使用聯合 PRIMARY KEY（如 `user_id` + `org_id`）。
- 單獨查詢其中一個欄位時，另外為該欄位建立 Index。
- SQL 是最後防線，不能只依賴程式檢查。

```php
#[BeforeSaveEvent]
public static function beforeSave(BeforeSaveEvent $event): void
{
    $data = $event->data;
    $orm = $event->orm;

    // Bad：沒有排除自己（id != x），已存在的項目永遠無法儲存
    $exists = $orm->from(static::class)
        ->where('alias', $data['alias'])
        ->get();

    // OK：有排除自己，但沒有 Unique Key，併發新增時仍可能重複
    $exists = $orm->from(static::class)
        ->where('alias', $data['alias'])
        ->where('id', '!=', $data['id'] ?? 0)
        ->get();

    // Good：排除自己，並加入條件（同分類下 alias 不重複），搭配 Unique Key 防併發
    $exists = $orm->from(static::class)
        ->where('alias', $data['alias'])
        ->where('category_id', $data['category_id'])
        ->where('id', '!=', $data['id'] ?? 0)
        ->get();

    if ($exists) {
        throw new ValidateFailException('Duplicated alias');
    }
}
```

## 詳細參考

- 查詢與分組細節：參見 `references/knowledge/` 與 `references/development-app/`
- 測試資料庫行為：參見 `references/test/testing.md`




