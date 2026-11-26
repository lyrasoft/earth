# SQL Code Style

# 基本概念

手寫 SQL 時，保留字要全用大寫，其他的文字符號 (當然被引號包起來的文字為例外) 為小寫：

```
SELECT id, title, alias FROM articles WHERE id = 1;
```


# Query Builder

使用 Windwalker 時，盡量採用 query builder 編寫語法：

```php
// Create query object
$query = $orm->from(Article::class);
$query = $db->createQuery(Article::class);

// Build Select
$query->select('article.*')
    ->from(Article::class, 'article')
    ->where('article.id', 1);
    
// Get
$query->get();
$query->all();
```


# Join

Join 時使用的資料表 alias，請使用語意化的單數名稱如： article, user, category，避免使用 a, b, c 等令人混淆的別名，除非當下的字母 alias 是有意義的或縮寫，例如 `q`, `mk`。

```php
// Bad
$query->select('article.*', 'category.*')
	->from(Article::class, 'a')
	->leftJoin(
		Category::class,
		'c',
		'c.id',
		'a.category_id'
	);
	
// Good
$query->select('article.*', 'category.*')
	->from(Article::class, 'article')
	->leftJoin(
		Category::class,
		'category',
		'category.id',
		'article.category_id'
	);
	
// OK
$query->select('mdm.*')
	->from(MentionDailyMetric::class, 'mdm')
	->leftJoin(
		MentionDailyMetricSummary::class,
		'mds',
		'mds.id',
		'mdm.category_id'
	);
```

# 跳脫與引號

對於動態插入的欄位名稱，或者明確與保留字衝突的名稱，皆需以 DB name quote 來包裹：

```php
// 以下皆同
$query->where('id', 1);
$query->whereRaw($query->quoteName('id') . ' = 1');
$query->whereRaw($query->qn('id') . ' = 1');
$query->whereRaw('%n = 1', 'id');
$query->whereRaw(query->format('%n = 1', 'id'));
```

對於內容字串，應該以字串引號來包裹並同時跳脫：

```php
// 以下皆同
$query->where('title', 'Foo');
$query->whereRaw('title = ' . $query->quote('Foo'));
$query->whereRaw('title = ' . $query->q('Foo'));
$query->whereRaw('title = %q', 'Foo');
$query->whereRaw($query->format('title = %q', 'Foo'));
```

具體正確用法請見 <https://windwalker.io/documentation/components/query/expressions.md>

## 字串引號

由於雙引號在 MySQL 以外的資料庫，等同於反引號，是用在包裹欄位的，所以請勿在手動組 MySQL 查詢字串時，用雙引號包裹字串

```php
// Good
$query->whereRaw("foo = 'foo'");
$query->whereRaw('foo = \'foo\'');
$query->whereRaw('foo = %q', 'foo');

// Bad
$query->whereRaw('foo = "foo"'); // <-- 在 PostgreSQL or MSSQL 內會壞掉
```
