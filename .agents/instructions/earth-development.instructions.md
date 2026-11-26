---
applyTo: '**'
---
# Earth development

Earth is the LYRASOFT website host application, built on Windwalker Core 4. It also hosts editable Core, Unicorn, and Luna package repositories under `vendor/`.

## Required boundaries

- Identify whether a change belongs to Earth or to `vendor/windwalker/core`, `vendor/windwalker/unicorn`, or `vendor/lyrasoft/luna` before editing. Each package has its own Git repository, contributor guidance, and tests; if a commit is requested, make package commits in that package repository, not at the Earth root.
- Ask before editing any other package under `vendor/`.
- Edit asset sources, not generated output under `www/assets/`.
- Do not run `php windwalker run preparedev` against a database whose data must be preserved; it resets migrations and seeds data.
- Do not guess site URLs, login methods, or build procedures. Use the relevant Earth references and ask when required.
- For Windwalker, Unicorn, or Luna API usage, search the official Windwalker documentation first. Inspect vendor source only when the documentation does not answer the question.

## Route by task

| Task | Read first |
| --- | --- |
| Earth structure and where to change code | `./architecture.instructions.md`, `../docs/earth/conventions.md` |
| Earth versus Core / Unicorn / Luna changes and package tests | `../docs/earth/vendor-packages.md` |
| CLI commands and environment | `../skills/windwalker/references/knowledge/commands.md` |
| Frontend assets, Vite / Fusion, and runtime asset resolution | `./assets.instructions.md`, `../docs/earth/assets-and-builds.md` |
| Unicorn JS authoring or PHP adapter work | `../docs/earth/unicorn-js-development.md` |
| DI, app environment, or secrets | `./services.instructions.md`, `../skills/windwalker/references/knowledge/di-injection.md` |
| PHPUnit, coverage, smoke test, or browser test | `./test.instructions.md`, `../skills/test/SKILL.md` |
| MVC generator or disposable CRUD page | `../skills/windwalker/references/development-app/mvc-generation.md`, `../skills/test/references/frontend-scratch-page.md` |

The legacy Earth development files remain under `.github/dev/` for existing links and details not yet represented here. Use this `.agents` entry and its linked references as the canonical routing guidance.
