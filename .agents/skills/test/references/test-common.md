---
applyTo: "**/{test,Test,tests,Tests}/**/*.php"
---

# 共用測試指令

新增、更新與執行測試的完整流程（包含本機 PHPUnit 範圍設定與使用 coverage），請見 [test 技能](../SKILL.md)。
`@codeCoverageIgnore` 的規則請見 [coverage 指令](coverages.md)。

## 測試方法優先使用 `#[Test]`

優先使用 `#[Test]` attribute（`PHPUnit\Framework\Attributes\Test`），而非 `test` 方法名稱前綴，並以描述性名稱說明被測行為。
不要使用 `@test` docblock annotation。

```php
// Bad
public function testFindReturnsNullWhenMissing(): void

// Good
#[Test]
public function findReturnsNullWhenMissing(): void
```

若只是修改一個一致使用 `test` 前綴的檔案中的少數方法，請維持既有風格。

## 生命週期 hook 優先使用 attribute

優先使用 `#[Before]` / `#[After]`、`#[BeforeClass]` / `#[AfterClass]`、`#[PreCondition]` / `#[PostCondition]`，
而非傳統 hook（`setUp()`、`tearDown()` 等）。請從 `PHPUnit\Framework\Attributes` 匯入；使用描述性方法名稱、`void` 回傳，
類別層級 hook 使用 static 方法。避免空的 hook，遷移時須保留父類別／trait 的行為與執行順序。

## 為測試 fixture 與整合目標加上說明

為僅供測試使用的 fixture 與整合目標加上類別 docblock，說明其角色，以及依賴它的行為或測試。並註明何時可以移除
（例如被覆蓋的行為已移除且沒有測試再引用它），讓後續維護者能分辨刻意保留的測試支援程式碼與無用程式碼。

## 已棄用／建議使用的 API（快速規則）

新的「使用／不要使用」規則請以條列方式加在這裡，方便快速掃讀。

### 例外訊息比對

不要使用 `expectExceptionMessage()`（PHPUnit 13 已棄用）。

- 完全符合或子字串比對，使用 `expectExceptionMessageIsOrContains()`。
- 完全符合，使用 `expectExceptionMessageIs()`。
- 正規表示式比對，使用 `expectExceptionMessageMatches()`。

### PHPUnit Mock：呼叫次數 matcher

PHPUnit mock 的呼叫次數 matcher 不要使用 `self::`，請使用 `$this->`
（適用於 `once()`、`never()`、`any()`、`exactly($n)`、`atLeastOnce()`、`atMost($n)`）。

```php
// Bad
$mock->expects(self::once())->method('foo');

// Good
$mock->expects($this->once())->method('foo');
```

### Test Double（Mock / Stub）

當受測對象會呼叫許多其他物件或服務時，請用 test double 隔離，而不是為每個測試建立真實資料或接上真實依賴。
只有在被測行為本身就是整合時，才使用真實 fixture／資料庫。

使用 PHPUnit 13 API：

- 只提供回傳值（沒有 expectation）的 double，使用 `$this->createStub(Foo::class)`，以 `->method('x')->willReturn(...)` 設定。
  不要對 stub 呼叫 `expects()`。
- 只有在測試要驗證互動（如 `expects($this->once())`）時，才使用 `$this->createMock(Foo::class)`。沒有 `expects()` 的 mock
  會被回報為 notice，請改用 `createStub()`。
- 多介面 double 使用 `createStubForIntersectionOfInterfaces()` / `createMockForIntersectionOfInterfaces()`。
- 使用 `willReturnCallback()`、`willReturnOnConsecutiveCalls()` 或 `willReturnMap()`；`withConsecutive()` 已不存在。
- 不要使用 `setMethods()`、`addMethods()` 或 `onlyMethods()`；請改為 mock 介面或抽出協作者。
- 單純的值物件優先使用匿名類別或小型具名測試 stub。

```php
// Good: 只需要回傳值
$repo = $this->createStub(UserRepository::class);
$repo->method('find')->willReturn($user);

// Good: 驗證互動
$logger = $this->createMock(LoggerInterface::class);
$logger->expects($this->once())->method('error');
```
