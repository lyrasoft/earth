# MVC quick generate for test scenarios

When a task needs a throwaway admin CRUD page for a test scenario, such as reproducing or debugging a bug in generated list or edit pages, use the [mvc-quick-generate skill](../skills/mvc-quick-generate/SKILL.md).

- Follow the skill's Debug workflow: generate a disposable set, test it, then delete every generated file.
- Confirm with `git status` that no generated files remain.
- Do not edit `vendor/windwalker/unicorn` templates unless the task requires it. Read the skill's Templates section first.
