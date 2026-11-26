---
applyTo: "**/{test,Test,tests,Tests}/**/*.php"
---

# Test Coverage Instructions

For the workflow of finding and filling coverage gaps, see the [test skill](../skills/test/SKILL.md).

## Exclude logic-free getters/setters or simple proxy methods from code coverage and tests

Do not write separate tests solely to exercise pure getters/setters (only return or assign a property, optionally
`return $this`). If such a method has no meaningful coverage through other behavior tests, exclude it from code
coverage with `@codeCoverageIgnore` in the method docblock. If logic is later added (validation, normalization, lazy
init, side effects, etc.), you **must** remove the ignore tag and cover the behavior with tests.

```php
/**
 * @codeCoverageIgnore
 */
public function setOptions(array $options): static
{
    $this->options = $options;

    return $this;
}
```

Also exclude simple proxy methods that only call another method or service without adding meaningful behavior.
But if the parameters or return value are transformed, validated, or otherwise processed, cover the behavior with tests.

```php
/**
 * @codeCoverageIgnore
 */
public function createAnyInstance(array $options): static
{
    return $this->builder->createAnyInstance($options);
}
```

## Never ignore branches with logic or thrown errors

`@codeCoverageIgnore` is only for logic-free getters/setters and simple proxy methods. Any branch that contains logic
(conditions, transformation, fallback, side effects) or throws an exception **must** be tested, even if it is only a
few lines. Do not ignore such branches to raise coverage. Only code that truly cannot be exercised (e.g. an
unavailable external driver or process-level `exit`/signal handling) may be ignored, with the reason stated in the tag.

A branch that is purely defensive or a fallback and is proven unreachable through the public behavior (e.g. a caller
already guarantees the condition) may also be ignored. Wrap it with `@codeCoverageIgnoreStart` / `End` (or tag the
method) and explain in a comment why it cannot be reached. Verify the claim first; if it is reachable, test it.
