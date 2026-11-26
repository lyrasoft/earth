# MVC Work Planning Guide

## Purpose

Before creating an MVC, plan how the tables, models, controllers, routes, views, forms, and frontend assets relate to each other, to avoid duplicated work.

## Documents to Read First

- [Windwalker MVC Architecture](https://windwalker.io/documentation/unicorn/mvc-crud/mvc-concepts.md)
- [Code Generation](https://windwalker.io/documentation/unicorn/mvc-crud/code-generation.md)
- [ORM Entities](https://windwalker.io/documentation/framework/services/database/entities.md)
- Windwalker MVC generator commands `../../../windwalker/references/development-app/mvc-generation.md`
- Testing guide `../../../test/SKILL.md`
- Look up any local or remote documents related to this task.
- For the available documentation search API, see `../../../windwalker/SKILL.md`, or use the Windwalker Docs MCP.

## Required Parameters

- `$stage`: Frontend/backend location (Front/Admin), or a specified Stage name (PascalCase)
- `$subject`: Table name or Entity name (PascalCase)

## Workflow

- Read all specified documents thoroughly to get a high-level understanding of the MVC architecture, how to use the generator, and the Unicorn MVC List / Item architecture.
- Use the askQuestion tool to ask the user for the `$stage` and `$subject` parameters.
- Based on any additional instructions from the user, plan:
  - The table columns of this Entity ($subject), recommended indexes, whether related tables are needed, etc.
  - Following the Windwalker ORM style, plan special column castings: JSON, Enum, DateTime, Record, etc.
  - The Edit View / Form Fields that correspond to the columns above.
  - The accompanying Services, how they work, access strategies, etc.
  - Any other code planning and flow required to complete this task
- Create a Markdown plan document named `plan.<subject>.md` in the project's `tmp/plans/` directory
  - Include everything planned above, keeping the section hierarchy clear and using plain text and lists so the user can make necessary edits.
  - After editing, the plan can be handed to an agent for direct execution.
  - For the plan template, see `../../assets/plan.Flower.example.md`; create the plan document following this template.
  - `<!-- agent: ... -->` in template comments are notes for the agent; remove them when generating the actual document.
- During actual execution, follow the user's instructions in order. With the user's permission, you may actually run migrations & seeders to verify the generated results.

## Execution Notes

- If the documentation provides generator commands, avoid generating by hand and use the commands whenever possible.
- You may manually modify the generated code according to the plan.
- E2E tests or unit tests can be used to verify that the generated code meets expectations.

## Verification

- Execute each step in the plan document one by one and confirm that each result meets expectations.
- Review the generated code and database structure to ensure they match the plan. You can use MCP to inspect the database contents.
- E2E tests can be used to check the generated frontend output.
- Unit tests can be used to check that the backend code behaves as expected.
