---
name: lyrasoft-best-practice
description: LYRASOFT project development best practices, covering Windwalker ORM, coding standards, styling, and review guidance, with recommended approaches, Bad/OK/Good examples, and references.
---

# Skill: lyrasoft-best-practice

## Purpose

Provide shared development best practices for LYRASOFT projects so developers can quickly identify recommended approaches while writing or reviewing code, with links to detailed documentation.

## When to use

- When writing or modifying code in a LYRASOFT project and unsure of an approach or whether it follows project conventions.
- When reviewing whether a project conforms to company development standards.

## Scope and guidance

- Windwalker ORM best practices (query grouping, typed entities, N+1 queries, duplicate-data protection): @references/windwalker/orm-best-practices.md
- Coding standards (`references/cs/`):
  - BEM naming concepts and conventions: @references/cs/bem.md
  - CSS / SCSS coding style: @references/cs/css-cs.md
  - JS / TypeScript coding style: @references/cs/js-cs.md
  - PHP coding style: @references/cs/php-cs.md
  - SQL coding style and Query Builder: @references/cs/sql-cs.md
- Lint tools and usage (`references/cs/`):
  - PHP linting and `php-cs-fixer` usage: @references/cs/php-lint.md
- Structure and naming (`references/organizing/`):
  - CSS / SCSS structure and organization: @references/organizing/css-scss-organizing.md
  - PHP / Windwalker Service organization: @references/organizing/php-service-organizing.md
  - Modern Vue project structure and syntax conventions: @references/organizing/vue-service-organizing.md
  - VueI18n locale key naming conventions: @references/organizing/vuei18n-locale-key-naming.md
  - Windwalker locale key naming conventions: @references/organizing/windwalker-locale-key-naming.md
- Styling (`references/styling/`):
  - Bootstrap SCSS customization and variable overrides: @references/styling/bootstrap-scss.md
  - Bootstrap Dark Mode best practices: @references/styling/bootstrap-dark-mode.md
  - Design System implementation standards: @references/styling/design-system.md
- Review (`references/review/`):
  - PHP code review guidance: @references/review/review-php.md
- Deliberately out of scope: framework installation, deployment configuration, and private rules for a single project.

## Expected behavior

- Identify the task's topic first, then read the corresponding reference.
- Follow the Good examples; when encountering a Bad approach, explain why and provide a correction.
- Consult links in the references for detailed reasoning and full explanations rather than repeating long material in the response.
- Keep results concise, clear, and consistent with project conventions.

## Verification

- The result fits the task and repository context.
- No unnecessary or unrelated changes have been introduced.
- Instructions remain concise and reusable.
