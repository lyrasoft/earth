# ORM and Database Operation Best Practices

## Grouping and typing query results

### Prefer automatic grouping

Prefer using the ORM's `groupByJoins()` to group all joined columns into child objects.

```php
$items = $orm->from(Item::class)
    ->leftJoin(Foo::class, 'foo', 'foo.item_id', 'item.id')
    ->groupByJoins()
    ->all(Item::class);

foreach ($items as $item) {
    $foo = $item->foo; // Foo object (Collection::class)
}
```

Documentation: [ORM Querying](https://windwalker.io/documentation/components/orm/querying.md)

### Convert objects to Entities or declare their types where possible

Hydrate grouped child objects as Entities inside the loop:

````php
foreach ($items as $item) {
    $foo = $item->foo; // Foo object (Collection::class)
    $foo = $orm->toEntity($foo, Foo::class); // Foo Entity
    $foo = $orm->tryEntity($foo, Foo::class); // Foo Entity or NULL
}
````

When referencing an object from a template, declare its type in advance so the IDE can track it:

```blade
<?php
/** @var Foo $foo */
?>
{{ $foo->id }}
```

Documentation: [ORM Entities](https://windwalker.io/documentation/components/orm/entities.md)

## Best practices for join queries

### Avoid N+1 queries in loops

When each Item on a list page needs multiple Foos, **fetch them in a batch** instead of querying one item at a time.

```php
// Bad: dynamically add a property or query each item in a loop (N+1)
foreach ($items as $item) {
    $item->foos = $this->orm->from(Foo::class)->where('item_id', $item->id)->all(Foo::class)->dump();
}

// OK: provide a getter in the ViewModel; suitable only for small amounts of data
// ViewModel::getFoos(Item $item): array
//   $this->orm->from(Foo::class)->where('item_id', $item->id)->all(Foo::class)->dump()
// Blade: call $vm->getFoos($item) inside @foreach ($items as $item)

// Good: collect IDs, fetch all at once, and group by itemId (a page should have no more than 50 items)
$ids = $items->column('id')->dump();

$fooGroup = $this->orm->from(Foo::class)
    ->where('item_id', $ids, [0]) // Prevent an empty array from generating IN ()
    ->order('foo.ordering', 'ASC')
    ->groupByJoins()
    ->all(Foo::class)
    ->groupBy('itemId');
```

Use them directly in Blade:

```html
<?php $foos = $fooGroup[$item->getId()] ?? []; ?>
@foreach ($foos as $foo)
    <span class="badge bg-primary">{{ $foo->getTitle() }}</span>
@endforeach
```

Note: Batch fetching requires all items to be available first, so it does not apply to iterators.

## Avoid duplicate data

Priority order: database Unique Key (most reliable and best performance) > Entity `beforeSave` check (can add extra conditions).

- If a Unique Key allows nulls, the column must be NULLABLE; do not use an empty string (NULL is not treated as a duplicate).
- For many-to-many mapping tables, use a composite PRIMARY KEY (such as `user_id` + `org_id`).
- If one column is queried independently, create an Index for that column as well.
- SQL is the last line of defense; do not rely only on application checks.

```php
#[BeforeSaveEvent]
public static function beforeSave(BeforeSaveEvent $event): void
{
    $data = $event->data;
    $orm = $event->orm;

    // Bad: does not exclude itself (id != x), so existing items can never be saved
    $exists = $orm->from(static::class)
        ->where('alias', $data['alias'])
        ->get();

    // OK: excludes itself but has no Unique Key, so concurrent inserts can still create duplicates
    $exists = $orm->from(static::class)
        ->where('alias', $data['alias'])
        ->where('id', '!=', $data['id'] ?? 0)
        ->get();

    // Good: excludes itself and adds a condition (alias must be unique within the same category), with a Unique Key to prevent races
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

## Detailed references

- Querying and grouping details: see `references/knowledge/` and `references/development-app/`
- Testing database behavior: see `references/test/testing.md`
