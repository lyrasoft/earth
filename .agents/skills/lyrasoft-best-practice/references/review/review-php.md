# PHP Code Review Guidance

When reviewing code changes, PRs, or diffs, follow these rules.

## Review priorities

Review in this order:

1. Correctness: logic errors, edge cases, null / empty value handling, off-by-one errors, race conditions.
2. Security: input validation, authorization checks, SQL / XSS / injection risks, sensitive information exposure.
3. Project conventions: patterns in `.github/instructions/` (PHP DDD structure, Nuxt structure, routes, DTOs, enums).
4. Tests: new behavior has tests under `tests/`; existing tests still reflect behavior after the changes.
5. Style: PHP follows PSR-12; frontend follows Airbnb ESLint.

## PHP type reflection

Check whether PHP code loses type information. Recommend a correction if the IDE or static analysis cannot infer a type.

### Complex arrays, objects, and `json_decode` results

When data is a multi-field array, an object, or decoded JSON, recommend adding a shape to `@param`, `@return`, or `@var`.

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

### `mixed` and `array` in function signatures

Recommend concrete type declarations or generics instead of `mixed` or a bare `array`.

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

### Entities passed between variables

When an Entity loses its type as it passes through an array, `compact()`, serialization, or an untyped variable, recommend declaring its shape in a class-level PHPDoc.

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

## Project-specific checks

- Flag any changes that modify, delete, or commit `.env`; these are blockers.
- Flag committed credentials, API keys, or tokens (for example, `CLERK_SECRET_KEY`).
- Flag unrelated changes mixed into the diff.
- Flag comments that merely restate obvious code. Comments should explain only non-obvious logic.
- Flag new frontend code that depends on Nuxt auto-import, because this project does not use it.

## SQL review

Review SQL performance in code and migrations. Before recommending a new index, inspect indexes and keys in `resources/migrations/`.

- Confirm that the column is not already covered by an existing index, primary key, or unique key. Index prefixes count too (for example, `addIndex(['user_id', 'role_id'])` already covers `user_id`).
- When a query uses both `WHERE` and `ORDER BY`, recommend a composite index with equality-condition columns first and range or sorting columns afterward.
- Flag `SELECT *` on large tables and unbounded queries without `LIMIT`.
- Flag functions applied to indexed columns in `WHERE` (for example, `DATE(created)`), because they prevent index use.
- Flag leading-wildcard `LIKE '%x'` and `OR` across different columns; these patterns usually cannot use indexes.
- Flag N+1 query patterns inside loops.
- When an index is needed, identify the migration file to update and follow the project's `$schema->addIndex()` or `$schema->addUniqueKey()` pattern.
- Mark performance issues with Low severity, since indexes can be added later. Use High or Medium for logic errors or data integrity issues.

```php
// Existing: addUniqueKey(['user_id', 'org_id'])
// Query: WHERE org_id = ? AND user_id = ? ORDER BY created DESC
// Suggest: addIndex(['org_id', 'user_id', 'created'])
```
