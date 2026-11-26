# PHP Code Style

# 前言

LYRASOFT 採用 [PER](https://www.php-fig.org/per/coding-style/) 標準作為主要開發風格。

另外，本文件後續將補充一些 PER 中沒有規範，但我們另行規範的風格，所有未列出或出現衝突的規範皆以 PER 為準。

# 程式寫作規範

## 引入程式

當手動直接載入程式檔案時，請用 `require` 與 `require_once`，若採用程式動態載入（例如使用 `Factory` 模式），則使用 `include` 與 `include_once`。

引入程式請以關鍵字寫法，不要用函式寫法。

正確：

```php
require_once 'foo.php';
```

不應使用括號：

```php
require_once('foo.php');
```

一般來說，我們應該盡量用絕對路徑，搭配 Joomla! 常數來引入檔案，避免位置變更時出錯：

```php
require_once JPATH_LIBRARIES . '/foo/bar.php';
```

若要採用相對路徑，也應該使用 `__DIR__` 來當做路徑基礎：

```php
require_once __DIR__ . '/../bar.php';
```

## 檔案宣告 strict types

每個 PHP 原始檔都必須以 `declare(strict_types=1);` 開頭：

```php
<?php

declare(strict_types=1);

namespace Windwalker\Foo;
```

## 使用單引號

除非需要變數插值，字串一律使用單引號。

錯誤：

```php
$name = "Simon";
```

正確：

```php
$name = 'Simon';
$greet = "Hello, $name";
```

## new 不加括號

PHP 8.4 以上可省略 `new` 表達式的括號，直接串接方法呼叫。

錯誤：

```php
(new FooBar())->run();
```

正確：

```php
new FooBar()->run();
```

# 程式排版

## 換行

### 控制結構

所有控制結構 `if`, `for`, `foreach` 等等，皆須在前後增加一行空行。

```php
$foo = 'bar';

if ($foo) {
    //
}

$baz = 123;
```

### `return` 前空行

所有的 `return` 關鍵字上方必要空一行。

```php
$result = $db->loadObject();

return $result;
```

### 程式邏輯運作過程

編碼過程，建議每一行不同的邏輯控制皆空行，並且使用括號區隔的 Scope 空間上下也需要空行。

若連續變數賦值則不需空行。

範例：

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

# 參照

當使用變數參照時，參照符號應該距離等號一個空白字元，且跟後面的變數緊鄰，沒有空白。

範例:

```php
$ref1 = &$this->sql;
```

# 空白間距

## 字串連接

當使用字串連接時， `.` 符號的左右兩旁應該要有一格空白。例如：

```php
$id = 1;
echo 'index.php?option=com_foo&task=foo.edit&id=' . (int) $id;
```

多行字串，串接符號應該要在下一行的開頭，左方除了縮排以外不需額外的空格：

```php
$id = 1
echo 'index.php?option=com_foo&task=foo.edit&id=' . (int) $id
    . '&layout=special';
```

## 多行結尾逗號

多行陣列、函式參數與參數宣告允許保留結尾逗號：

```php
$array = [
    'foo',
    'bar',
];
```

## 型別轉換

使用 `()` 語法進行型別轉換時一定要在後方加上一個空白

```php
$sql = (string) $query;
```

# 判斷語法

## 強行別判斷

只要能確保型別不是變動的，我們皆以三等號 (`===` or `!==`) 來判斷，對效能有幫助，除非幫下判斷變數可能存在  
型別的變動(例如 `'1'` 與 `1`)，才使用 `==` 或 `!=`。

可以用 `$var === null` 取代 `is_null()`，效能較好。

確定是陣列時，可以用 `$var === []` 取代 `!count($var)` 或 `empty($var)` 來判斷空陣列 ，效能也較好。

其餘未列出的使用情境皆可用此規則來判斷。

## 存在判斷

當我們為了避免因變數或陣列元素未宣告而造成 Notice 時，可考慮用專用的判斷函式在 if 中。

### isset()

用在判斷變數或陣列是否宣告過，且值不為 `null`：

```php
if (isset($array['eggs']) && $array['eggs'] == 5) {
    //
}
```

也可以用 Nullish 判斷符

```php
if ($array['eggs'] ?? null) {
    //
}
```

或 Null Coalescing Operator

```php
$array['foo'] ??= 123;
```

### empty()

用在判斷變數或陣列元素是否被宣告過且值不為否（含 `null`, `false`, `0`, `''`, `[]`）等，一般作為布林值判斷，且內容是動態的，可能不曾被初始化時才用：

```php
if (!empty($array['online'])) {
}
```

注意 `isset()` 在變數為 `null` 時會回傳否，因此判斷物件屬性是否被宣告過，應該用 `property_exists()`。

### 簡單易懂

避免多重否定寫法混淆使用者：

```php
if (!$allowNull && !(($value !== null) && ($value !== ''))) {
}
```

## Docblock

Docblock 目前的 `@param` 與 `@return` 元素間空格為 2

```php
/**
 * @param  string  $foo
 * @param  int     $bar
 * @return  string
 */
```

## 換行

大多數的 function call, function 宣告等等，如果超過 120 個字元，建議換行，並且每一個參數都換行。

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

以下是 PER 允許的寫法與我們更偏好的寫法:

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

我們的 PHP-CS-Fixer 設定檔可能會長這樣，這補充了一些 PER 不足的部分:

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

## 執行方式

指令依各專案 `composer.json` 的 scripts 而定。在專案根目錄執行，建議只檢查與修正變更過的 PHP 檔案，避免影響無關程式：

```bash
composer cs-check -- --path-mode=intersection packages/cache/src/CachePool.php
composer cs-fix -- --path-mode=intersection packages/cache/src/CachePool.php
```

`--path-mode=intersection` 會保留 Finder 的排除設定。修正後請再執行一次檢查並檢視 diff。直接執行 `composer cs-check` / `composer cs-fix` 會掃描整個設定範圍，僅在確定需要時使用。
