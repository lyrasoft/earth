# Smoke tests and temporary execution files

## Purpose

This file provides development-environment knowledge for agents performing smoke tests outside unit tests or writing
temporary execution files to verify application or package behavior.

Use it for ad hoc validation that needs the host application's runtime or services. PHPUnit test-writing and coverage
rules remain in the separate testing instructions.

For general CLI usage and bootstrap details, see [the CLI instructions](cli.instructions.md).

## Temporary files

Put temporary execution files under the repository's `tmp/` directory, not at the repository root or in public
directories such as `www/`. `tmp/.gitignore` ignores temporary content while retaining its directory configuration
files. Ignored files are still local files, not an isolated or disposable environment.

Use a distinctive filename, such as `tmp/smoke-schedule.php`, so it does not overwrite another developer's files.
Remove only the files and artifacts created for the current check after testing. Do not clear the whole `tmp/`
directory: it may contain unrelated work, exports, and runtime data.

## Discovering and generating commands

Run commands from the repository root. The examples use `php windwalker`, which works without relying on executable
permissions or a Unix shebang. On systems that support it, `./windwalker` is equivalent.

```sh
php windwalker list
php windwalker help exec
php windwalker g
php windwalker g command SmokeCheckCommand --about
```

For generator subcommand help, use `--about`, not `-h`, which describes the outer generator command.

To generate an application command:

```sh
php windwalker g command SmokeCheckCommand --ns 'App\Command' --auto-register smoke:check --no-interaction
php windwalker smoke:check
```

By default, the command generator writes to `src/Command/` and ensures the class name ends in `Command`.
`--ns` sets the namespace; it does not change the output directory. Use `--dir` if a different destination is needed,
and keep the directory consistent with Composer autoloading.

Registration in `resources/registry/commands.php` happens only when an auto-registration name is supplied, either
through `--auto-register` or the interactive prompt. Supplying the name and `--no-interaction` avoids waiting for
input in agent-driven runs. Do not use `--force` to overwrite an existing command.

Generated commands implement `CommandInterface`, receive `IOInterface` in `execute()`, and may use constructor
injection for application services. Return `0` for success and a nonzero code for a failed check.

After a temporary command is no longer needed, delete its generated class and remove only its registration entry.
Do not delete `resources/registry/commands.php`, which is shared by all application commands.

## Custom scripts

`resources/registry/scripts.php` defines named batches of shell commands. It is loaded through
`etc/app/console.config.php`.

```sh
php windwalker run
php windwalker run <scriptName>
```

Running `run` without a script name lists the available scripts and their commands. Read the definition before
executing it: a script may install dependencies, change files, or modify the database. In particular, `preparedev`
resets migrations and seeds data.

Prefer an existing suitable entry point over adding a registry entry for a one-off check. If a temporary script entry
is necessary, use a unique name and remove only that entry afterward, preserving other developers' changes.

## REPL and ad hoc PHP execution

The `exec` command boots the host application's console runtime before entering PsySH or executing PHP.
Composer autoloading, application bootstrap helpers, and configured services are available.

```sh
php windwalker exec
php windwalker exec --code 'get_class($app)'
php windwalker exec tmp/smoke-check.php
```

The execution scope exposes `$app` (the application) and `$io` (console input/output). Use `$app->service(...)` to
resolve configured services, `$app->config(...)` to read configuration, and `$app->path(...)` to resolve application
path aliases. Do not print secrets or entire configuration arrays.

File mode takes a positional path, not a `--file` option. Relative paths are resolved from the current working
directory. A PHP file should start with `<?php` and `declare(strict_types=1);`; do not manually bootstrap the
application again inside it.

**Current implementation caveat:** Core's `src/Core/Command/ReplCommand.php` resolves the file path into `$file` but
includes `$real`, which is undefined. Until that implementation is corrected, do not rely on file mode as a working
validation path. Use the REPL, a read-only `--code` expression, or a temporary registered command instead.

The current `--code` implementation tries expression evaluation first and retries as statements on any exception.
Avoid mutating operations there: a failure can cause the code to execute twice. Prefer a command for checks that
write data or need controlled error handling.

## Runtime boundaries and safe verification

These checks run against the host application's configured services and environment, not PHPUnit fixtures.
Required databases, queues, or external services must be available, and invoking them may affect real data.
Confirm the target environment before writes; do not reset or seed a database without explicit authorization.

The console application is not a web request. `src/Console/Application.php` does not enable the web simulator by
default. Do not assume HTTP request state, routing, sessions, or web-only services are initialized; use the proper web
entry point when those are the behavior being checked.

Define observable success and failure conditions before running a check. For automated runs, prefer a bounded,
non-interactive command with an explicit exit status. Output alone, especially a dump or a file's return value, is not
proof that the expected behavior passed.

When testing resident workers or servers, limit execution where supported and stop only the process started for the
check. Clean up its temporary files, registry entries, and test-created artifacts afterward. Report unavailable
prerequisites or incomplete checks explicitly; do not treat skipped checks as passing.

Smoke tests supplement rather than replace persistent PHPUnit tests. Keep package regression tests in the owning
package's test suite, following that package's guidance.

## Web and rendering smoke tests

Use a temporary controller and route when verification needs a real HTTP request, request-scoped services, or rendered
HTML. Use a temporary middleware when the behavior under test belongs to the request pipeline. These files must live
in their normal application locations to participate in autoloading and routing; unlike files in `tmp/`, they may
appear in Git status.

Inspect the generators before creating the temporary components:

```sh
php windwalker g controller --about
php windwalker g middleware --about
php windwalker g view --about
php windwalker g controller 'Front\Smoke\SmokeController' --no-interaction
php windwalker g middleware SmokeProbeMiddleware --no-interaction
```

The controller generator defaults to `src/Module/` and `App\Module`; a stage-qualified name avoids its stage-selection
prompt and places this example in `src/Module/Front/Smoke/`. Middleware generation defaults to `src/Middleware/`
and `App\Middleware`. Generation alone does not register either component in the request pipeline.

### Register a narrow temporary route

Follow the neighboring route definitions. `routes/main.route.php` loads the front-end and admin groups:

- Front-end routes load from `routes/front/*.php` and `routes/custom/front/*.route.php`.
- Admin routes load from `routes/admin/*.php` and `routes/custom/admin/*.route.php`, with the `/admin` URL prefix.

For example, a temporary file in `routes/front/` can register a unique GET endpoint:

```php
<?php

declare(strict_types=1);

use App\Module\Front\Smoke\SmokeController;
use Windwalker\Core\Router\RouteCreator;

/** @var RouteCreator $router */
$router->get('smoke_check', '/__smoke-check')
    ->handler(SmokeController::class);
```

Implement the controller behavior before requesting the endpoint. For rendering checks, attach a view model with
`->view(...)` and follow `src/Module/Front/Home/HomeView.php` for `#[ViewModel]`, `#[ViewPrepare]`, and the module-local
layout pattern. Assert a distinctive rendered element or value, not just that the controller was reached.

Attach a probe middleware to this route with `->middleware(SmokeProbeMiddleware::class)` when route-level
instrumentation is sufficient. For behavior before routing, inspect the `middlewares` configuration in
`etc/app/main.config.php` and the merged application configuration. Preserve `RoutingMiddleware` and the existing
pipeline; remove the temporary registration afterward.

### Exercise and verify the real request

Start the local server with an explicit port as described in [the CLI instructions](cli.instructions.md), then request
the endpoint using an HTTP client or browser. Use the normal web entry point, or `/dev.php` when development-mode
execution is required; `www/dev.php` selects the development environment and includes `www/index.php`.

Check the HTTP status, relevant headers, and response body. Inspect redirects before following them: canonicalization,
maintenance, locale handling, and admin authentication can redirect or reject a request before the controller runs.
Front-end middleware also loads configuration and assets, so a rendering check may require a configured database and
available frontend assets. Do not bypass authentication or CSRF protection just to make a smoke test pass.

If the expected behavior depends on JavaScript or CSS, verify it in a browser and check console/network errors;
an HTML response alone does not verify browser behavior.

Keep temporary endpoints local and short-lived. Do not expose secrets, arbitrary code execution, or destructive actions
through them. Remove the temporary controller, middleware, route, view/layout, and any generated assets or registrations
created for the check. Preserve unrelated files and stop only the server process started for this task.
