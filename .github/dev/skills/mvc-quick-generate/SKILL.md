---
name: mvc-quick-generate
description: Generate a full admin CRUD set (entity, migration, repository, list/edit pages, route) in one command with Windwalker unicorn generators, and delete a disposable copy after debugging auto-generated pages.
---

# MVC Quick Generate

## Purpose

Generate a complete admin CRUD page set with one command: entity, migration, seeder, controller, list (grid) view, edit view, and route. Use it to build a backend page quickly, or to reproduce a bug in auto-generated pages. Generate a disposable copy, test it, then delete it.

## When to use

- Creating a new admin CRUD module.
- Reproducing a bug in auto-generated list/edit pages. Generate a copy with a distinct name, test it, and delete it.

## Command

```shell
php windwalker g unicorn:mvc-admin <Name> -y
```

`<Name>` is PascalCase singular, e.g. `Product`. `-y` answers yes to prompts. Sub-steps run in this order:

1. `unicorn:model <Name>`: creates the entity in `src/Entity/` and the repository in `src/Repository/`. Entities are normally pre-generated, so this step is what produces the repository. The `g entity` generator creates an empty entity, so do not use it for this workflow.
2. `mig:create <Name>Init <Name> --no-update`: creates a migration in `resources/migrations/`. **It is not executed.**
3. `seed:create <kebab-name>`: creates `resources/seeders/<kebab-name>.seeder.php`.
4. `unicorn:controller <name>`: creates the controller under `src/Module/<Name>/`.
5. `unicorn:view-grid` and `unicorn:view-edit`: create the list and edit views, grid and edit forms, `views/`, and `assets/`.
6. `unicorn:route <kebab-name>`: creates `routes/<kebab-name>.route.php`.

Options passed through: `--ns`, `--dir`, `--force`, `-y`, `--grid`, `--edit`.

## Look up official docs first

Before generating, or when options or behavior are unclear, check the official Windwalker docs instead of guessing. Use either source:

- MCP tool `windwalker-docs-search`: natural-language query, for example `generator --pkg option`.
- `https://windwalker.io/llms.txt`: index of doc pages (each page has a `.md` URL); fetch the relevant page.

Fall back to reading generator code (`vendor/windwalker/core/src/Core/Generator/`, `vendor/windwalker/unicorn/src/Generator/`) only if the docs do not answer.

## Generating into a vendor package (e.g. Luna)

Confirmed in the official docs and in `AbstractGeneratorSubCommand`:

- `-p, --pkg=<name>` makes a sub-command use that package's namespace and directory. The name comes from the package's `getName()` (the composer name unless `$name` is set), for example `lyrasoft/luna`. `g entity` and `unicorn:model` support it.
- `unicorn:mvc-admin` and `unicorn:mvc-simple` do **not** accept `--pkg`. Use `-d, --dir` (relative to the host root) and `--ns` instead.
- `--ns` changes only the module class namespace. Entity and repository imports in the templates still point to `App\Entity` and `App\Repository`; fix them by hand after generating into a package.
- `mig:create` has `-d, --dir` only (no `--pkg`): pass the package's `resources/migrations` directory.
- Generated files in a package belong to that package's git repo; commit there, not in Earth. See [vendor-packages](../../docs/vendor-packages.md).

Generated output may differ from the examples above, so review it and run `php -l` on each file.

## Known behavior

- The generator prints `Also generate model? [Y/n]` up to three times. It did not block, and exit code was 0 with `-y`.
- The migration is only written. Run `php windwalker mig:go` yourself, and only on a dev database, since it changes the real schema.
- Files under `routes/` are auto-loaded, so the generated route file needs no registration.
- The admin sidebar is not generated. Add an entry by hand in `resources/menu/admin/sidemenu.menu.php`, using the same pattern as the existing entries: `$menu->link($lang('unicorn.title.grid', title: $lang('luna.<entity>.title')))->to($nav->to('<snake>_list'))`.

## Debug shortcut

For a simple bug, skip the `g entity` step and run `unicorn:mvc-admin` (`mvs`) directly. It generates the entity as well.

## Templates

To change what gets generated, edit the templates under `vendor/windwalker/unicorn/views/code/`. Do not edit the generated files as a fix for the generator itself.

- `controller/` – controller class
- `model/entity/` and `model/repo/` – entity and repository
- `route-admin/` and `route-simple/` – route files (`routes/<kebab>.route.php`)
- `view/grid/` – list page: grid form, list view, `assets/` (scss/ts), `views/` (list, modal, toolbar blades)
- `view/edit/` – edit page: edit form, edit view, `assets/`, `views/` (edit blade, toolbar blade)
- `view/list/` and `view/item/` – alternative list and item page templates
- `workflow/` – workflow class

File names are templates too, using `{% pascal($name) %}` and `{% kebab($name) %}` placeholders. Files end in `.tpl`.

Vendor files are overwritten by `composer install`/`update`. Copy any change you want to keep into the project, or record it in a dedicated note before updating dependencies.

## Debug workflow (disposable copy)

1. Generate with a distinct name, e.g. `DebugTest<Name>`.
2. Run the migration if the page needs a table (`php windwalker mig:go`), and reproduce the bug.
3. When finished, delete everything the generator created:
   - `src/Entity/<Name>.php`, `src/Repository/<Name>Repository.php`
   - `resources/migrations/*_<Name>Init.php`
   - `resources/seeders/<kebab-name>.seeder.php`
   - `src/Module/<Name>/` (whole directory)
   - `routes/<kebab-name>.route.php`
   - The menu entry added to `resources/menu/admin/sidemenu.menu.php`
   - Roll back and drop the table if it was migrated.
4. Confirm with `git status` that no leftover files remain.

## Validation

- `git status` shows only the intended files after cleanup.
- Generated list and edit pages load in the browser before cleanup.
