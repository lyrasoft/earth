# Command-line interface

## Running commands

Run the project's CLI from the repository root using PHP and the root `windwalker` entry point. PHP and Composer
dependencies must be available; the entry point loads `vendor/autoload.php`.

```sh
php windwalker list
php windwalker help <command>
php windwalker <command> [arguments] [options]
```

Use `list` to discover available commands and `help` to check their arguments and options before executing them.
Use `-v`, `-vv`, or `-vvv` when more diagnostic output is needed.
Use `--no-interaction` for automated execution only after supplying all required arguments and choices.
On systems that support executable PHP scripts, `./windwalker` is equivalent to `php windwalker`.

## Bootstrap and registration

The root `windwalker` script loads `etc/define.php` and Core's CLI bootstrap. The runtime then loads
`etc/runtime.config.php`, creates `App\Console\Application`, and loads `etc/app/console.config.php`.

- `resources/registry/commands.php` registers application commands by name and class.
- `resources/registry/scripts.php` defines named batches of shell commands, invoked with `php windwalker run <script>`.
- Framework and package configuration also contribute commands; use `list` rather than assuming a command is installed.

`etc/define.php` loads environment files in order: `.env.base`, `.env`, `.env.local`, `.env.<APP_ENV>`, then
`.env.<APP_ENV>.local`. Later files override earlier values; the environment-specific filename uses the current
`APP_ENV`, falling back to `prod`. Do not assume a command is using development settings simply because it runs locally.
The same file defines path constants, which `etc/runtime.config.php` exposes as aliases such as `@public` (`www/`)
and the default `@temp` (`tmp/`).

## Named scripts and safety

For example, `php windwalker run prepare` installs frontend dependencies and builds assets.

Read the script definition before running a named script. Some scripts modify dependencies, generated files, or the
database. In particular, `php windwalker run preparedev` resets migrations and seeds data; do not run it against data
that must be preserved.

For ad hoc runtime checks or temporary execution files, also read [the smoke-test instructions](smoke-test.instructions.md).

## Code generation

`g` lists the available generator tasks. Core provides tasks such as `command`, `controller`, `view`, `model`,
`entity`, `form`, and `route`; packages may add others. Application additions are registered in
`resources/registry/generator.php`.

```sh
php windwalker g
php windwalker g command SmokeCheckCommand --about
php windwalker g command SmokeCheckCommand --ns 'App\Command' --auto-register smoke:check --no-interaction
```

Use `--about` for a generator task's help, not `-h`, which describes the outer command. Inspect the task's options
before generation: output directories and namespaces depend on the task. For commands, the default directory is
`src/Command/`; `--ns` changes the namespace, while `--dir` changes the destination directory.

Command registration is optional: `--auto-register <name>` or the interactive prompt adds an entry to
`resources/registry/commands.php`. Do not assume every generated command is registered.
Avoid `--force` unless overwriting existing files is explicitly intended.

Migrations use the separate `mig:create` command; inspect `php windwalker help mig:create` rather than assuming
they are a `g` task. Keep schema changes in `resources/migrations/`.

## Development servers

`server:start` (also available as `server:run`) currently dispatches to the `php` or `swoole` engine. The optional
positional server argument uses `engine:name`, defaulting to `php:main`.

### PHP's built-in server

```sh
php windwalker server:start
php windwalker server:start php:main --host localhost --port 8080
```

Without a custom main file, the PHP engine serves `www/`; use `--docroot` to override the document root.
The default host is `localhost`. With no port specified, the command searches for an available port starting at
`8000`, not `8080`. Use an explicit `--port` for predictable URLs and read the startup output for the actual address.

The PHP engine invokes `php -S`, so `php` must also be available on the child process's PATH. Its startup output
shows the site URL and `/dev.php` development entry point. This is a local development server, not a production
deployment strategy.

### Named servers and custom main files

`resources/registry/servers.php` maps an engine and name to a main-file path. Its `php` and `swoole` maps are currently
empty. For example, after a `website` entry is registered under `swoole`:

```sh
php windwalker server:start swoole:website --host localhost --port 8080
php windwalker server:stop swoole:website
```

`--main` supplies a main file directly. If that file does not exist, the command falls back to the named registry entry.
`--main` does not select the engine; specify the appropriate `engine:name` explicitly:

```sh
php windwalker server:start swoole:website --main servers/swoole_website.php --port 8080
```

For PHP, a main file is passed to `php -S` as its router script; the current implementation uses it instead of the
`-t` document-root argument. For Swoole, an existing main file is mandatory.
Server selection comes from the registry and CLI options, not an `etc/server.config.php` file in this host.

Swoole requires a compatible PHP Swoole extension and, for the supplied application templates, `windwalker/reactor`.
Use a supported runtime; do not assume the Windows PHP installation can run Swoole.
The Swoole engine accepts `--app`, `--workers`, `--task-workers`, `--max-requests`, and `--watch`.
Its managed process state is stored under `@temp/servers/`.

Reactor main-file templates are available in this checkout under
`vendor/windwalker/framework/packages/reactor/resources/servers/`. They can be published with the
`swoole_website` and `swoole_websocket` resource tags described below. Review published templates before running
them: their bootstrap and configuration paths must match this host application's layout.

`server:stop` currently supports Swoole, not PHP's built-in server. For PHP, stop the foreground process with
Ctrl+C or terminate only the process started for the current task. Do not stop unrelated servers.
After starting a server for a check, verify that it responds rather than treating a startup message as proof.

## Package resource installation

`pkg:install` publishes resources from already installed and discovered Windwalker packages into the host
application. It does not download Composer dependencies. Packages may provide configuration, routes, migrations,
assets, server entry points, or other files, and run callbacks after publishing.

```sh
php windwalker help pkg:install
php windwalker pkg:install
php windwalker pkg:install windwalker/reactor --tag swoole_website --dry-run
php windwalker pkg:install windwalker/reactor --tag swoole_website
```

Without tags or `--all`, the command prompts for packages/resource tags; supplying a package name filters that
selection. For automation, specify the package and tags. `--all` requires explicit package names and publishes all
resources for those packages.

Use `--dry-run` to preview file operations first. Existing destinations are normally preserved; `--force` allows
overwrites and should be used only when intended. Review both copied files and callback-generated changes afterward,
including registry entries. The `swoole_websocket` tag may also generate application code, not just a server file.

Publishing host customization files is different from changing a package's implementation. For implementation
changes, follow the application/package boundaries in [the development instructions](../copilot-instructions.md).
