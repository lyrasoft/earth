---
applyTo: '**'
---
# Skill lookup guidance

Before starting a task, determine which skills are relevant to its type, then read the corresponding `SKILL.md` and references before implementing, answering, or reviewing. A task may involve multiple skills; load all of them.

## Guidance for searching project knowledge

- Windwalker official documentation:
  - LLMS.txt <https://windwalker.io/llms.txt>
  - Search API <https://docs-search.windwalker.io/search> - POST JSON: `{"messages": [{"role": "user","content": "Search text..."}]}`
  - Chat API <https://docs-search.windwalker.io/chat/completions> - OpenAI Chat Completions API
  - MCP tool `windwalker-docs-search`
- Confluence / LYRASOFT Wiki search:
  - MCP tool `com.atlassian/atlassian-mcp-server`
- Search historical code in the `lyrasoft` GitHub organization:
  - MCP tool `io.github.github/github-mcp-server`

## Writing, modifying, running, or reviewing tests

For writing, modifying, running, or reviewing tests (PHPUnit, Windwalker / PHP, E2E):

- @./review.instructions.md Code review and security review guidance.
- @./test.instructions.md Testing guidance.
- @../skills/windwalker/references/test/testing.md Windwalker testing guidance.

## PHP / Windwalker development

For Windwalker framework development (MVC, DI, CLI, Vue, and Unicorn JS / PHP collaboration):

- @./windwalker.instructions.md Windwalker development guidance.
- @./cli.instructions.md Available CLI commands.
- @./cs.instructions.md Code style guidance.
- @./services.instructions.md Windwalker service organization guidance.
- @../skills/windwalker/SKILL.md See the list of Windwalker development skills.
- @../skills/lyrasoft-best-practice/SKILL.md See the list of LYRASOFT best-practice skills.

### Creating backend MVC / Entity / Migrations, etc.

- @../skills/windwalker/references/development-app/mvc-generation.md MVC scaffolding guidance.
- @../skills/windwalker/references/knowledge/di-injection.md DI injection guidance.
- <https://windwalker.io/documentation/framework/packages/package-override.md> Package Override guidance.

### Frontend development and theming in Windwalker

For frontend JS development or HTML / Blade / CSS / SCSS / Vue theming in Windwalker:

- @./assets.instructions.md Frontend assets and build guidance.
- @./cs.instructions.md Code style guidance.
- @../skills/lyrasoft-best-practice/references/cs/css-cs.md CSS / SCSS standards.
- @../skills/lyrasoft-best-practice/references/styling/design-system.md Design System theming standards.
- @../skills/lyrasoft-best-practice/references/organizing/css-scss-organizing.md CSS / SCSS organization standards.
- @../skills/lyrasoft-best-practice/references/styling/bootstrap-scss.md Bootstrap / SCSS standards.
- @../skills/windwalker/references/development-app/vue-development.md Vue development guidance.

### Windwalker / Unicorn / Luna internal package development

For modifying Windwalker Components / Core / Unicorn / Luna packages, or developing new packages:

- @./architecture.instructions.md Overall project architecture guidance.
- @../skills/windwalker/references/development-core/package-development.md Package development guidance.

## Creating or editing instructions / skills / docs

- @./instruction.instructions.md Guidance for writing instructions / skills / docs.

## Unclear task types

Read all instructions and the relevant skills' `SKILL.md` and references. In your response, explain which task type and skills you consider most appropriate, and why.

## Task type reference

- @../instructions See the complete list of instructions.
- @../skills/index.md See the complete list of skills.

## Principles

- When a task matches multiple types, load all relevant skills; do not choose only one.
- If a skill conflicts with project guidance, follow the project's guidance and explain the difference in your response.
- Load only references relevant to the current task; avoid reading the entire skills directory at once.
- After adding a skill, update `skills/index.md` and the corresponding mapping in this guidance.
