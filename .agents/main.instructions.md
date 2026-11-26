# Main agent guidance entry

## Purpose

This file provides in-repository agent guidance, including operating rules and constraints for contributors and AI agents.

## Project overview

See @../AGENTS.md

## Project structure

See @instructions/architecture.instructions.md

## Earth application development

For Earth-specific development constraints and task routing, see @instructions/earth-development.instructions.md.
Earth-specific long-form references are indexed in @docs/earth/README.md.

## Guidance document directory

- @README.md: Project overview and shared standards
- @AGENTS.md: Basic guidance for contributors and AI agents
- `instructions`: Repository-level rules and content-routing guidance
  - Keep these as concise bullet points that direct tasks to the appropriate documents.
  - They may be split by task type and file applicability, using `applyTo:` front matter to specify where they apply.
  - They may point to remote documents or MCP tools; search for and read task-relevant material before starting whenever possible.
  - @./instructions/README.md: Instructions overview and entry point
- `skills`: Shared skills and templates
  - Composable skills that include multi-step workflows, examples, and resources
  - Can be installed or copied from remote sources as needed to enable a capability
  - May include different skills for different task types
  - @./skills/index.md: Skills overview and entry point
  - @./skills/<name>/references/: References related to a skill
- `docs`: Long-form documents and templates
  - Load only when needed; may contain framework and library documentation, project knowledge, or operating procedure (SOP) material.
  - @./docs/index.md: Documentation overview and entry point
- `prompts`: AI workflow prompts

### Docs sections and long-document loading rules

`docs` may contain longer reference documents. Follow a just-in-time loading approach; do not read the entire directory up front:

- @docs/index.md: Start here for the document overview and section index to quickly determine what is relevant.
- `docs/templates/`: Document templates, structure, and writing guidance; consult this when creating or revising documents.
- When adding a docs category, update this section so future users know when to load it.
- If unsure which document to read, start with @docs/index.md, then locate only the relevant long-form document for the topic.

Keep these sections concise and extensible so new documentation categories can be added without rewriting the whole file.
