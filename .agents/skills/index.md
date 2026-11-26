# Skills index

This directory holds reusable, cross-project skills and operational guidance that can be applied across different repositories and work contexts.

## Scope

- repository setup guidance
- reusable task patterns
- team operations checklists
- AI-assisted workflow instruction templates

## Naming conventions

Content in this folder should use different filenames based on purpose:

- [Directory or project overview](../../../README.md)
- `SKILL.md`: formal definition of a single skill
- `*.prompt.md`: reusable prompt templates
- [Repository-level instructions](../../../AGENTS.md) / `.agents/main.instructions.md`

In other words, if a file is a skill itself, name it `SKILL.md` rather than `README.md`. `README.md` should be reserved for overview and index content.

## Organization

- [LYRASOFT Best Practices](lyrasoft-best-practice/SKILL.md)
- [Windwalker](windwalker/SKILL.md)
- [test](test/SKILL.md): workflow for adding, updating, and running PHPUnit tests
- [templates](templates/): shared starter templates for skills and guidance

## Maintenance

This folder should stay focused on broadly useful company knowledge. Avoid storing project-specific or product-specific content here unless it is intentionally designed as a reusable template.
