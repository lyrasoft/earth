# Development instructions

This directory contains focused rules that complement the [Earth development instructions](../copilot-instructions.md).
Keep repository-wide guidance there concise and link to topic-specific requirements here.

## Contents

- [instruction.instructions.md](instruction.instructions.md): routes content between instructions, skills, docs, and prompts
- [test-common.instructions.md](test-common.instructions.md): PHPUnit test-writing conventions for PHP test files
- [test-coverage.instructions.md](test-coverage.instructions.md): rules for `@codeCoverageIgnore` and untestable branches
- [smoke-test.instructions.md](smoke-test.instructions.md): environment knowledge for smoke tests and temporary execution files outside unit tests
- [cli.instructions.md](cli.instructions.md): running the host application's CLI, discovering commands, and invoking named scripts

Keep mandatory rules short and actionable. Long-form background belongs in `docs/`, reusable workflows in `skills/`,
and task templates in `prompts/`.
