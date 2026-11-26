---
name: windwalker
description: A skill for the Windwalker framework, providing commands, documentation, and skills for Windwalker project development.
---

# Skill: windwalker

## Purpose

When an agent starts developing a new feature, this skill provides the commands, documentation, and guidance for Windwalker project development so the agent can get up to speed quickly and follow project conventions.

## When to use

- Developing a new feature or fixing code in a project.
- Checking or reviewing a project's code, documentation, or instructions.

## Scope

This skill generally applies to projects developed with PHP / the Windwalker framework. These may be full-stack Windwalker projects or projects that use Windwalker as a backend with a separate frontend app. Typically, the project root is a Windwalker project and contains files and directories such as `www/`, `assets/`, `composer.json`, and `.env`.

## Expected behavior

- Confirm that the project uses Windwalker.
- Read all instructions and documentation related to Windwalker project development, and follow the project's conventions.
- Use the relevant references, documentation, and commands in this skill as required by the task.

## Related documentation

- Windwalker official documentation: <https://windwalker.io/llms.txt>
  - Windwalker Search API: <https://docs-search.windwalker.io/search> - POST JSON: `{"messages": [{"role": "user","content": "Search text..."}]}`
  - Chat API <POST https://docs-search.windwalker.io/chat/completions> - OpenAI Chat Completions API
  - Search MCP tool: `windwalker-docs-search`
- Core knowledge @references/knowledge/
  - Available CLI commands and usage: @references/knowledge/commands.md
  - DI container and service registration: @references/knowledge/di-injection.md
  - I18n and multilingual development: @references/development-app/i18n.md
  - Unicorn JS and PHP collaboration workflow: @references/knowledge/unicorn-js-script-pipeline.md
  - Vite / Fusion build and AssetService asset resolution: @references/knowledge/frontend-and-assets.md
- Application development @references/development-app/
  - MVC module generator commands and notes: @references/development-app/mvc-generation.md
  - Vue components, mounting, and plugin configuration: @references/development-app/vue-development.md
- Core development @references/development-core/
  - Package development and editing: @references/development-core/package-development.md
- Testing
  - Windwalker testing tasks: @references/test/testing.md

## Verification

- Revisit the instructions and references to confirm that the agent used the commands and documentation for Windwalker project development, without overlooking or failing to follow any relevant guidance.
