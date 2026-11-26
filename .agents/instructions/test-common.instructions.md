---
applyTo: "**/{test,Test,tests,Tests}/**/*.php"
---

# Shared Testing Instructions

For the full workflow of adding, updating, and running tests (including local PHPUnit scope settings and using
coverage), see the [test skill](../skills/test/SKILL.md). For `@codeCoverageIgnore` rules, see the
[coverage instructions](test-coverage.instructions.md).

## Prefer `#[Test]` for test methods

Prefer the `#[Test]` attribute (`PHPUnit\Framework\Attributes\Test`) over the `test` method-name prefix, and give
methods descriptive names that state the behavior under test. Do not use the `@test` docblock annotation.

```php
// Bad
public function testFindReturnsNullWhenMissing(): void

// Good
#[Test]
public function findReturnsNullWhenMissing(): void
```

Keep the existing style when only editing a few methods in a file that consistently uses the `test` prefix.

## Prefer attributes for lifecycle hooks

Prefer `#[Before]` / `#[After]`, `#[BeforeClass]` / `#[AfterClass]`, and `#[PreCondition]` / `#[PostCondition]`
over conventional hooks (`setUp()`, `tearDown()`, etc.). Import from `PHPUnit\Framework\Attributes`; use descriptive
method names, `void` returns, and static methods for class-level hooks. Avoid empty hooks and preserve inherited
parent/trait behavior and execution order when migrating.

## Document test fixtures and integration targets

Add a class docblock to test-only fixtures and integration targets explaining their role and the behavior or tests that
depend on them. State when the fixture can be removed (for example, once the covered behavior is removed and no tests
reference it), so future maintainers can distinguish intentional test support from unused code.

## Deprecated / Preferred APIs (Quick Rules)

Add new "use / don't use" rules here as bullets so they stay easy to scan.

### Exception Message Matchers

Don't use `expectExceptionMessage()` (deprecated in PHPUnit 13).

- Use `expectExceptionMessageIsOrContains()` for exact-or-substring match.
- Use `expectExceptionMessageIs()` for an exact match.
- Use `expectExceptionMessageMatches()` for a regex match.

### PHPUnit Mock – Invocation Count Matchers

Don't use `self::` for PHPUnit mock invocation count matchers; use `$this->` (applies to `once()`, `never()`,
`any()`, `exactly($n)`, `atLeastOnce()`, `atMost($n)`).

```php
// Bad
$mock->expects(self::once())->method('foo');

// Good
$mock->expects($this->once())->method('foo');
```

### Test Doubles (Mock / Stub)

When the subject calls many other objects or services, isolate it with test doubles instead of building real data or
wiring real dependencies for every test. Use real fixtures/DB only when the behavior under test is the integration
itself.

Use the PHPUnit 13 APIs:

- `$this->createStub(Foo::class)` for a double that only supplies return values (no expectations). Configure with
  `->method('x')->willReturn(...)`. Do not call `expects()` on a stub.
- `$this->createMock(Foo::class)` only when the test verifies interactions via `expects($this->once())` etc. A mock
  with no `expects()` is reported as a notice; use `createStub()` instead.
- `createStubForIntersectionOfInterfaces()` / `createMockForIntersectionOfInterfaces()` for multi-interface doubles.
- Use `willReturnCallback()`, `willReturnOnConsecutiveCalls()` or `willReturnMap()`; `withConsecutive()` no longer exists.
- Don't use `setMethods()`, `addMethods()` or `onlyMethods()`; mock an interface or extract a collaborator instead.
- Prefer anonymous classes or small named test stubs for plain value objects.

```php
// Good: only needs a return value
$repo = $this->createStub(UserRepository::class);
$repo->method('find')->willReturn($user);

// Good: verifies the interaction
$logger = $this->createMock(LoggerInterface::class);
$logger->expects($this->once())->method('error');
```
