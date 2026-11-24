# Earth development instructions

Earth is a Windwalker Core 4 host app that also hosts the editable packages `windwalker/core`, `windwalker/unicorn`,
and `lyrasoft/luna` (each its own git repo under `vendor/`). First identify which one the task targets (see
[vendor-packages](docs/vendor-packages.md)); that decides the guidance, tests, and repo to commit in.
This file is a routing guide: identify the task type, then read the listed files before acting. Indexes: [instructions](instructions/README.md), [skills](skills/index.md),
[docs](docs/index.md). The general-purpose layer is [host guidance](../copilot-instructions.md).

## Always

- Edit source, never generated output (`www/assets/`).
- Never run DB reset/seed commands (`php windwalker run preparedev`) against data that must be preserved.
- Do not guess the site URL, login, or build flow; follow the files below and ask the user when they say to.
- Ask before editing a `vendor/` package other than one the task targets.

## Read by task type

| Task | Read first |
| --- | --- |
| Understand structure, routing, where to change code | [architecture guide](../docs/architecture.md), [conventions](docs/conventions.md) |
| Run build, install, lint (phpcs) | [commands](docs/commands.md) |
| Add or change PHP tests, or run tests for a `vendor/` package (Core, Unicorn, Luna) | [vendor-packages test modes](docs/vendor-packages.md#test-modes-all-three-packages) (read first), [test skill](skills/test/SKILL.md), [test-common](instructions/test-common.instructions.md), [test-coverage](instructions/test-coverage.instructions.md), [commands](docs/commands.md) |
| Smoke test or temporary execution script | [smoke-test](instructions/smoke-test.instructions.md) |
| Run the app CLI | [cli](instructions/cli.instructions.md) |
| Edit assets / Vite / Fusion | [fusion-assets](instructions/fusion-assets.instructions.md), [AssetService](docs/asset-service.md) |
| Unicorn `*Script`, PHP-to-JS pipeline | [unicorn-scripts](instructions/unicorn-scripts.instructions.md), [pipeline](docs/php-js-script-pipeline.md) |
| Write unicorn-next JS or wrap a JS lib for PHP | [unicorn-js-style](instructions/unicorn-js-style.instructions.md), [unicorn-js-php-adapter](instructions/unicorn-js-php-adapter.instructions.md) |
| Front-end / browser (e2e) verification | [instructions README](instructions/README.md), [e2e-browser-test](instructions/e2e-browser-test.instructions.md), [skill](skills/e2e-browser-test/SKILL.md), [scratch page](instructions/frontend-scratch-page.instructions.md) |
| Generate or debug admin CRUD | [mvc-quick-generate](instructions/mvc-quick-generate.instructions.md), [skill](skills/mvc-quick-generate/SKILL.md) |
| Inject services, config, app env, secrets | [DI injection](docs/di-injection.md), [app-environment](docs/app-environment.md) |
| Develop Core, Unicorn, or Luna in `vendor/` (which package, its rules, tests, commit repo) | [vendor-packages](docs/vendor-packages.md) |
| Add or reorganize guidance | [instruction.instructions.md](instructions/instruction.instructions.md) |

## Documentation links

- When listing files or directories as an index, start each item with a Markdown link: `[label](relative/path): description`.
- In prose, use inline code for paths and identifiers; link only when useful for navigation.
