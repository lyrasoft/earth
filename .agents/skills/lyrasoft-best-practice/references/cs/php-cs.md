# PHP Code Style

# Introduction

LYRASOFT uses [PER](https://www.php-fig.org/per/coding-style/) as its primary coding standard.

The remainder of this document adds conventions that are not covered by PER. For any convention not listed here, or whenever this document conflicts with PER, follow PER.

# Coding conventions

## Including files

When directly loading a file manually, use `require` or `require_once`. For dynamic file loading (for example, with the Factory pattern), use `include` or `include_once`.

Use the keyword syntax for including files, not the function syntax.

Correct:

```php
require_once 'foo.php';
```

Do not use parentheses:

```php
require_once('foo.php');
```

In general, prefer absolute paths with Joomla! constants to avoid errors if file locations change:

```php
require_once JPATH_LIBRARIES . '/foo/bar.php';
```

If you use a relative path, use `__DIR__` as the path base:

```php
require_once __DIR__ . '/../bar.php';
```

## Declare strict types in files

Every PHP source file must begin with `declare(strict_types=1);`:

```php
<?php

declare(strict_types=1);

namespace Windwalker\Foo;
```

## Use single quotes

Use single quotes for strings unless interpolation is needed.

Incorrect:

```php
$name = "Simon";
```

Correct:

```php
$name = 'Simon';
$greet = "Hello, $name";
```

## Do not put parentheses after `new`

In PHP 8.4 and later, parentheses can be omitted from a `new` expression when chaining a method call.

Incorrect:

```php
(new FooBar())->run();
```

Correct:

```php
new FooBar()->run();
```

# Formatting

## Line breaks

### Control structures

Add a blank line before and after every control structure, such as `if`, `for`, and `foreach`.

```php
$foo = 'bar';

if ($foo) {
    //
}

$baz = 123;
```

### Blank line before `return`

Always leave a blank line before the `return` keyword.

```php
$result = $db->loadObject();

return $result;
```

### Program logic

During development, it is recommended to leave a blank line between different logical operations. Also leave blank lines immediately before and after a parenthesized scope.

Do not add blank lines between consecutive variable assignments.

Example:

```php
if ($avoid) {
    $filter = $this->getState('filter', []);
    $search = $this->getState('search');
    $data   = $this->getState('data', []);
    $table  = $this->getTable();

    $table->bind($data);

    $table->check();

    $table->store();
}
```

# References

When using a variable reference, put one space between the equals sign and the reference operator, and no space between the reference operator and the variable.

Example:

```php
$ref1 = &$this->sql;
```

# Spacing

## String concatenation

When concatenating strings, put one space on each side of the `.` operator. For example:

```php
$id = 1;
echo 'index.php?option=com_foo&task=foo.edit&id=' . (int) $id;
```

For multiline strings, put the concatenation operator at the beginning of the next line, with no extra space before it apart from indentation:

```php
$id = 1
echo 'index.php?option=com_foo&task=foo.edit&id=' . (int) $id
    . '&layout=special';
```

## Trailing commas on multiline constructs

Trailing commas are allowed in multiline arrays, function arguments, and parameter declarations:

```php
$array = [
    'foo',
    'bar',
];
```

## Type casting

When using `()` syntax for type casting, always add a space after the closing parenthesis:

```php
$sql = (string) $query;
```

# Conditional syntax

## Strict comparisons

Use the triple-equals operators (`===` or `!==`) whenever the type is known to be stable; they are more performant. Use `==` or `!=` only when the variable being checked may have a changing type (for example, `'1'` versus `1`).

Use `$var === null` instead of `is_null()` for better performance.

When you know a value is an array, use `$var === []` instead of `!count($var)` or `empty($var)` to check for an empty array; this is also more performant.

Use this rule for other cases not listed here.

## Existence checks

To avoid Notices caused by undeclared variables or array elements, consider using the dedicated checks in an `if` statement.

### `isset()`

Use it to check whether a variable or array element is declared and its value is not `null`:

```php
if (isset($array['eggs']) && $array['eggs'] == 5) {
    //
}
```

The nullish check can also be used:

```php
if ($array['eggs'] ?? null) {
    //
}
```

Or use the Null Coalescing Operator:

```php
$array['foo'] ??= 123;
```

### `empty()`

Use it to check whether a variable or array element is declared and has a truthy value (that is, not `null`, `false`, `0`, `''`, `[]`, etc.). Generally, use it for boolean checks on dynamic content that may not have been initialized:

```php
if (!empty($array['online'])) {
}
```

Note: `isset()` returns false when a variable is `null`. Therefore, use `property_exists()` to check whether an object property has been declared.

### Keep it simple

Avoid confusing users with multiple negations:

```php
if (!$allowNull && !(($value !== null) && ($value !== ''))) {
}
```

## Docblocks

In Docblocks, use two spaces between the `@param` / `@return` elements and their values:

```php
/**
 * @param  string  $foo
 * @param  int     $bar
 * @return  string
 */
```

## Line wrapping

For most function calls, function declarations, and similar constructs, it is recommended to wrap lines longer than 120 characters and put each argument on its own line.

```php
$foo = $this->getFoo(
    $bar,
    $baz,
    $qux
);
public function getFoo(
    string $bar,
    string $baz,
    string $qux
): string {}
```

PER allows the following style, but we prefer the version below:

```php
// OK
foo($aLongParameter1, $aLongParameter2, [
    'a' => 1,
    'b' => 2,
], $aLongParameter3);

// Better
foo(
    $aLongParameter1,
    $aLongParameter2,
    [
        'a' => 1,
        'b' => 2,
    ],
    $aLongParameter3
);
```

## PHP-CS-Fixer

Our PHP-CS-Fixer configuration may look like the following. It supplements conventions that PER does not cover:

```php
return [
    '@PER-CS' => true,

    // Project conventions on top of PER Coding Style
    'declare_strict_types' => true,
    'new_expression_parentheses' => true,
    'new_with_parentheses' => [
        'anonymous_class' => true,
        'named_class' => true,
    ],
    'nullable_type_declaration_for_default_null_value' => true,
    'no_unused_imports' => true,
    'single_quote' => true,
    'trailing_comma_in_multiline' => false,
    'method_argument_space' => [
        'on_multiline' => 'ensure_fully_multiline',
    ],
    'concat_space' => [
        'spacing' => 'one',
    ],
    'array_syntax' => [
        'syntax' => 'short',
    ],
    'phpdoc_align' => [
        'align' => 'vertical',
        'spacing' => 2,
    ],
    'phpdoc_summary' => false,
    'phpdoc_to_comment' => false,
    'yoda_style' => false,
    'increment_style' => false,
    'global_namespace_import' => [
        'import_classes' => null,
        'import_constants' => null,
        'import_functions' => null,
    ],
];
```

## Running the fixer

The commands depend on each project's `composer.json` scripts. Run them from the project root. It is recommended to check and fix only changed PHP files to avoid affecting unrelated code:

```bash
composer cs-check -- --path-mode=intersection packages/cache/src/CachePool.php
composer cs-fix -- --path-mode=intersection packages/cache/src/CachePool.php
```

`--path-mode=intersection` preserves Finder's exclusion settings. After fixing, run the check again and inspect the diff. Running `composer cs-check` / `composer cs-fix` directly scans the entire configured scope; do so only when necessary.
