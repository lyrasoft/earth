# SQL Code Style

# Basic concepts

When writing SQL by hand, use uppercase for reserved words and lowercase for other text (except text enclosed in quotes):

```
SELECT id, title, alias FROM articles WHERE id = 1;
```

# Query Builder

When using Windwalker, prefer writing queries with the Query Builder:

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

# Joins

Use meaningful singular names for table aliases in joins, such as `article`, `user`, and `category`. Avoid confusing aliases such as `a`, `b`, and `c`, unless a letter alias is intentional or an abbreviation (for example, `q`, `mk`).

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

# Escaping and quoting

Quote dynamically inserted column names, or names that are explicitly known to conflict with reserved words, with a DB name quote:

```php
// All of the following are equivalent
$query->where('id', 1);
$query->whereRaw($query->quoteName('id') . ' = 1');
$query->whereRaw($query->qn('id') . ' = 1');
$query->whereRaw('%n = 1', 'id');
$query->whereRaw(query->format('%n = 1', 'id'));
```

Quote and escape content strings:

```php
// All of the following are equivalent
$query->where('title', 'Foo');
$query->whereRaw('title = ' . $query->quote('Foo'));
$query->whereRaw('title = ' . $query->q('Foo'));
$query->whereRaw('title = %q', 'Foo');
$query->whereRaw($query->format('title = %q', 'Foo'));
```

See <https://windwalker.io/documentation/components/query/expressions.md> for the correct usage.

## String quotes

In databases other than MySQL, double quotes are equivalent to backticks and are used to quote column names. Therefore, do not enclose strings in double quotes when manually composing MySQL query strings.

```php
// Good
$query->whereRaw("foo = 'foo'");
$query->whereRaw('foo = \'foo\'');
$query->whereRaw('foo = %q', 'foo');

// Bad
$query->whereRaw('foo = "foo"'); // <-- This breaks in PostgreSQL or MSSQL
```
