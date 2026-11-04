---
name: test
description: Add, update, and run PHP tests with PHPUnit, including handling local scope settings and using coverage to find missing tests.
---

# Skill: test

## Purpose

Provide a repeatable workflow for adding, updating, and running PHPUnit tests in the package being changed. Earth
currently has no root test suite. Writing conventions live in the
[testing instructions](../../instructions/test-common.instructions.md) and coverage rules in the
[coverage instructions](../../instructions/test-coverage.instructions.md); this skill covers the process.

## When to use

- adding or updating tests for new or changed behavior
- running a requested test scope and reporting the result
- the user asks to add missing tests or improve coverage

## Expected behavior

### Run tests

1. Identify the package and requested scope (a file, a suite, or the whole package).
2. Read that package's guidance and use its own namespace, PHPUnit configuration, and `vendor/bin/phpunit`.
3. Run the command from the package directory. For example, from `vendor/windwalker/core`, run
   `vendor/bin/phpunit tests/Core/Application`.
4. Preserve any pre-existing local PHPUnit configuration. If the requested scope is disabled, do not treat tests that
   were not run as passing; use an appropriate temporary configuration or CLI option, then remove temporary files.

### Add missing tests with coverage

1. When a coverage driver is available, run coverage for the requested scope to identify uncovered lines and branches.
2. Add meaningful behavior assertions for those gaps, including edge cases and error paths.
3. Rerun the same coverage scope to confirm the improvement.
4. Do not chase percentages with assertion-free tests or new coverage exclusions.
5. If coverage cannot run, report the limitation rather than claiming the gaps are covered.

## Validation

- the package and requested scope were actually executed, not silently skipped
- any temporary configuration was removed and the package's local PHPUnit configuration is unchanged
- new tests assert behavior and follow the shared testing and coverage instructions
- coverage claims are backed by a rerun of the same scope
